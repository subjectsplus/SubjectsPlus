import { useQuery, useMutation, useQueryClient } from 'react-query';

export function useFetchPluslets(sectionId) {
    if (sectionId === undefined) throw new Error('"sectionId" argument is required to call useFetchPluslets.');

    return useQuery(['pluslets', sectionId],
        () => fetchPluslets(sectionId, { pagination: false }), {
        select: data => data['hydra:member'],
        staleTime: 5000
    });
}

export function useFetchPluslet(plusletId) {
    if (plusletId === undefined) throw new Error('"plusletId" argument is required to call useFetchPluslet.');

    return useQuery(['pluslet', plusletId], () => fetchPluslet(plusletId));
}

export function useCreatePluslet(sectionId) {
    if (sectionId === undefined) throw new Error('"sectionId" field is required to call useCreatePluslet.');

    const queryClient = useQueryClient();
    return useMutation(createPluslet, {
        onMutate: async newPluslet => {
            await queryClient.cancelQueries(['pluslets', sectionId]);
            const previousPlusletsData = queryClient.getQueryData(['pluslets', sectionId]);
            
            queryClient.setQueryData(['pluslets', sectionId], {
                ...previousPlusletsData,
                'hydra:member': [...previousPlusletsData['hydra:member'], newPluslet],
            });
            
            return { previousPlusletsData };
        },
        onError: (error, newPluslet, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['pluslets', sectionId], context.previousPlusletsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['pluslets', sectionId]);
        },
    });
}

export function useDeletePluslet(sectionId) {
    if (sectionId === undefined) throw new Error('"sectionId" field is required to call useDeletePluslet.');

    const queryClient = useQueryClient();
    return useMutation(deletePluslet, {
        onMutate: async deletedPluslet => {
            await queryClient.cancelQueries(['pluslets', sectionId]);
            const previousPlusletsData = queryClient.getQueryData(['pluslets', sectionId]);

            const optimisticResult = produce(previousPlusletsData, draftData => {
                const updates = {};
                const columnPluslets = draftData['hydra:member'].filter(pluslet => pluslet.pcolumn === deletedPluslet.pcolumn)
                .filter(pluslet => pluslet.uuid !== deletedPluslet.plusletId);

                columnPluslets.forEach((pluslet, index) => {
                    if (pluslet.prow !== index) {
                        updates[pluslet.uuid] = {
                            prow: index
                        };
                    }
                });

                draftData['hydra:member'] = draftData['hydra:member'].filter((pluslet) => pluslet.uuid !== deletedPluslet.plusletId);
                draftData['hydra:member'].forEach(pluslet => {
                    if (updates[pluslet.uuid]) {
                        pluslet.prow = updates[pluslet.uuid].prow;
                    }
                });
                draftData['hydra:totalItems'] = draftData['hydra:member'].length;
            });
            
            queryClient.setQueryData(['pluslets', sectionId], optimisticResult);
            
            return { previousPlusletsData };
        },
        onError: (error, plusletData, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['pluslets', sectionId], context.previousPlusletsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['pluslets', sectionId]);
        }
    });
}

export function useReorderPluslet() {
    const queryClient = useQueryClient();
    return useMutation(reorderPluslet, {
        onMutate: async plusletData => {
            const sourceSection = plusletData.sourceSection;
            const sourceColumn = plusletData.sourceColumn;
            const sourceIndex = plusletData.sourceIndex;
            const destinationSection = plusletData.destinationSection;
            const destinationColumn = plusletData.destinationColumn;
            const destinationIndex = plusletData.destinationIndex;

            await queryClient.cancelQueries(['pluslets', sourceSection]);
            await queryClient.cancelQueries(['pluslets', destinationSection]);

            const sourcePlusletsData = queryClient.getQueryData(['pluslets', sourceSection]);
            const sourcePluslets = [...sourcePlusletsData['hydra:member']];
            const destinationPlusletsData = queryClient.getQueryData(['pluslets', destinationSection]);
            const destinationPluslets = [...destinationPlusletsData['hydra:member']];

            // Produce optimistic result
            if (sourceSection === destinationSection && sourceColumn === destinationColumn) {
                const columnPluslets = destinationPluslets.filter(pluslet =>
                    pluslet.pcolumn === destinationColumn).filter(
                        pluslet => pluslet !== undefined
                    );

                const updatedPluslets = {};

                // Move pluslet within the same column
                const [reorderedItem] = columnPluslets.splice(sourceIndex, 1);

                if (!reorderedItem) throw new Error('Failed to find source pluslet to reorder for optimistic result.');

                columnPluslets.splice(destinationIndex, 0, reorderedItem);

                // Index the updated prow
                columnPluslets.forEach((pluslet, index) => {
                    if (pluslet.prow !== index) {
                        updatedPluslets[pluslet.uuid] = {
                            prow: index
                        };
                    }
                });

                // Set the updated prow
                destinationPluslets.forEach(pluslet => {
                    if (updatedPluslets[pluslet.uuid]) {
                        pluslet.prow = updatedPluslets[pluslet.uuid].prow;
                    }
                });
            } else {
                const sourceColumnPluslets = sourcePluslets.filter(
                    pluslet => pluslet.pcolumn === sourceColumn).filter(
                        pluslet => pluslet !== undefined
                );
                
                const updatedPluslets = {};

                // Remove pluslet from source column
                const [reorderedItem] = sourceColumnPluslets.splice(sourceIndex, 1);
                
                if (!reorderedItem) throw new Error('Failed to find source pluslet to reorder for optimistic result.');

                // Pluslet must be removed from sourcePluslets if not the same section
                if (sourceSection !== destinationSection) {
                    sourcePluslets.splice(sourcePluslets.findIndex(pluslet => 
                        pluslet.uuid === reorderedItem.uuid), 1);
                }

                // Set the updated prow for source column pluslets
                sourceColumnPluslets.forEach((pluslet, index) => {
                    if (pluslet.prow !== index) {
                        updatedPluslets[pluslet.uuid] = {
                            prow: index,
                            pcolumn: sourceColumn
                        };
                    }
                });

                // Move pluslet to a different section/column
                const destinationColumnPluslets = destinationPluslets.filter(
                    pluslet => pluslet.pcolumn === destinationColumn).filter(
                        pluslet => pluslet !== undefined
                );

                // Add to destination column and reorder destination column pluslets 
                destinationColumnPluslets.splice(destinationIndex, 0, reorderedItem);

                // Pluslet must be added to destinationPluslets if not the same section
                if (sourceSection !== destinationSection) {
                    destinationPluslets.push(reorderedItem);
                }

                // Set the updated prow/pcolumn for destination column pluslets
                destinationColumnPluslets.forEach((pluslet, index) => {
                    // Note: possibly due to it not recognizing reorderedItem in conditional
                    if (pluslet.prow !== index || pluslet.pcolumn !== destinationColumn
                        || pluslet.uuid === reorderedItem.uuid) {
                        updatedPluslets[pluslet.uuid] = {
                            prow: index,
                            pcolumn: destinationColumn
                        };
                    }
                });

                // Set the updated prow/pcolumn
                sourcePluslets.forEach(pluslet => {
                    if (updatedPluslets[pluslet.uuid]) {
                        pluslet.prow = updatedPluslets[pluslet.uuid].prow;
                        pluslet.pcolumn = updatedPluslets[pluslet.uuid].pcolumn;
                    }
                });

                destinationPluslets.forEach(pluslet => {
                    if (updatedPluslets[pluslet.uuid]) {
                        pluslet.prow = updatedPluslets[pluslet.uuid].prow;
                        pluslet.pcolumn = updatedPluslets[pluslet.uuid].pcolumn;
                    }
                });

                // Sort the pluslets
                sourcePluslets.sort((plusletA, plusletB) => {
                    if (plusletA.pcolumn === plusletB.pcolumn) {
                        return plusletA.prow - plusletB.prow;
                    }
                    return plusletA.pcolumn - plusletB.pcolumn;
                });

                destinationPluslets.sort((plusletA, plusletB) => {
                    if (plusletA.pcolumn === plusletB.pcolumn) {
                        return plusletA.prow - plusletB.prow;
                    }
                    return plusletA.pcolumn - plusletB.pcolumn;
                });

                // Set optimistic result to query data
                queryClient.setQueryData(['pluslets', sourceSection], {
                    ...sourcePlusletsData,
                    'hydra:member': sourcePluslets,
                });

                queryClient.setQueryData(['pluslets', destinationSection], {
                    ...destinationPlusletsData,
                    'hydra:member': destinationPluslets,
                });

                return { sourcePlusletsData, destinationPlusletsData };
            }

            destinationPluslets.sort((plusletA, plusletB) => {
                if (plusletA.pcolumn === plusletB.pcolumn) {
                    return plusletA.prow - plusletB.prow;
                }
                return plusletA.pcolumn - plusletB.pcolumn;
            });

            queryClient.setQueryData(['pluslets', destinationSection], {
                ...destinationPlusletsData,
                'hydra:member': destinationPluslets,
            });

            return { destinationPlusletsData };
        },
        onError: (error, plusletData, context) => {
            // Perform rollback of pluslet order mutation
            console.error(error);
            
            if (context.sourcePlusletsData) {
                queryClient.setQueryData(['pluslets', plusletData.sourceSection], context.sourcePlusletsData);
            }

            queryClient.setQueryData(['pluslets', plusletData.destinationSection], context.destinationPlusletsData);
        },
        onSettled: (data, error, plusletData) => {
            // Refetch the pluslet order data
            if (plusletData.sourceSection !== plusletData.destinationSection) {
                queryClient.invalidateQueries(['pluslets', plusletData.sourceSection]);
            }
            queryClient.invalidateQueries(['pluslets', plusletData.destinationSection]);
        }
    });
}

async function fetchPluslets(sectionId, filters = null) {
    const data = await fetch(`/api/sections/${sectionId}/pluslets`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function fetchPluslet(plusletId) {
    const data = await fetch(`/api/pluslets/${plusletId}`);

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function createPluslet(initialPlusletData) {
    const plusletReq = await fetch('/api/pluslets', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(initialPlusletData)
    });

    if (!plusletReq.ok) {
        throw new Error(plusletReq.status + ' ' + plusletReq.statusText);
    }

    return plusletReq.json();
}

async function updatePluslet({ plusletId, data }) {
    if (plusletId === undefined) throw new Error('"plusletId" field is required to perform update pluslet request.');
    if (data === undefined) throw new Error('"data" field is required to perform update pluslet request');

    const req = await fetch(`/api/pluslets/${plusletId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    });

    if (!req.ok) {
        throw new Error(req.status + ' ' + req.statusText);
    }

    return req.json();
}

async function deletePluslet({ plusletId }) {
    if (plusletId === undefined) throw new Error('"plusletId" field is required to perform delete pluslet request.');

    const plusletToDelete = await fetchPluslet(plusletId);
    const sectionId = plusletToDelete['section'].split("/").pop();
    const {'hydra:member': pluslets } = await fetchPluslets(sectionId, {
        pagination: false,
        pcolumn: plusletToDelete['pcolumn']
    });
    const newPluslets = [...pluslets];
   
    // update the row index of the pluslets within the column
    newPluslets.splice(plusletToDelete.prow, 1);
    await Promise.all(newPluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index) {
            return fetch(`/api/pluslets/${pluslet.uuid}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    prow: index
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));

    // delete the section
    const req = await fetch(`/api/pluslets/${plusletId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': '*/*',
        }
    });

    if (!req.ok) {
        throw new Error(req.status + ' ' + req.statusText);
    }
    
    return req.text();
}

async function reorderPlusletWithinColumn(sectionId, column, sourceIndex, destinationIndex) {
    const { 'hydra:member': pluslets } = await fetchPluslets(sectionId, {
        pcolumn: column,
        pagination: false
    });

    // Move pluslet within the same column
    const [reorderedItem] = pluslets.splice(sourceIndex, 1);

    if (!reorderedItem) throw new Error('Failed to find source pluslet to reorder.');

    pluslets.splice(destinationIndex, 0, reorderedItem);

    // Perform the updating of the section index asynchronously
    return Promise.all(pluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index) {
            return fetch(`/api/pluslets/${pluslet.uuid}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    prow: index
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));
}

async function reorderPlusletAcrossSections(sourceSection, sourceColumn, sourceIndex, destinationSection,
    destinationColumn, destinationIndex) {
    const { 'hydra:member': sourceColumnPluslets } = await fetchPluslets(sourceSection, {
        pcolumn: sourceColumn,
        pagination: false
    });

    // Remove pluslet from source column
    console.log('(before) sourceColumnPluslets: ', sourceColumnPluslets);
    const [reorderedItem] = sourceColumnPluslets.splice(sourceIndex, 1);

    // Note: Issue occurs when dragging multiple pluslets consistently before
    // the mutation can "catch up", possibly need some sort of limiter, or if
    // too many requests at once, force a loader screen to catch up
    if (!reorderedItem) throw new Error('Failed to find source pluslet to reorder.');

    // Reorder source column pluslets
    await Promise.all(sourceColumnPluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index) {
            return fetch(`/api/pluslets/${pluslet.uuid}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    prow: index
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));

    // Move pluslet to a different section
    const { 'hydra:member': destinationColumnPluslets } = await fetchPluslets(destinationSection, {
        pcolumn: destinationColumn,
        pagination: false
    });

    // Add to destination column and reorder destination column pluslets
    console.log('(before) destinationColumnPluslets: ', destinationColumnPluslets);
    destinationColumnPluslets.splice(destinationIndex, 0, reorderedItem);
    console.log('(after) destinationColumnPluslets: ', destinationColumnPluslets);
    return Promise.all(destinationColumnPluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index || pluslet.pcolumn !== destinationColumn
            || pluslet.uuid === reorderedItem.uuid) {
            return fetch(`/api/pluslets/${pluslet.uuid}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    prow: index,
                    pcolumn: destinationColumn,
                    section: `/api/sections/${destinationSection}`
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));
}

async function reorderPlusletWithinSection(sectionId, sourceColumn, sourceIndex, destinationColumn, destinationIndex) {
    return reorderPlusletAcrossSections(sectionId, sourceColumn, sourceIndex, sectionId,
        destinationColumn, destinationIndex);
}

async function reorderPluslet({ sourceSection, sourceColumn, sourceIndex, destinationSection, destinationColumn, destinationIndex }) {
    if (sourceSection === undefined) throw new Error('"sourceSection" field is required to perform reorder pluslet request.');
    if (sourceColumn === undefined) throw new Error('"sourceColumn" field is required to perform reorder pluslet request.');
    if (sourceIndex === undefined) throw new Error('"sourceIndex" field is required to perform reorder pluslet request.');
    if (destinationSection === undefined) throw new Error('"destinationSection" field is required to perform reorder pluslet request.');
    if (destinationColumn === undefined) throw new Error('"destinationColumn" field is required to perform reorder pluslet request.');
    if (destinationIndex === undefined) throw new Error('"destinationIndex" field is required to perform reorder pluslet request.');

    if (sourceSection === destinationSection) {
        if (sourceColumn === destinationColumn) {
            // Move pluslet within the same column within the same section
            return reorderPlusletWithinColumn(sourceSection, sourceColumn, sourceIndex, destinationIndex);
        } else {
            // Move pluslet to different column within the same section
            return reorderPlusletWithinSection(sourceSection, sourceColumn, sourceIndex,
                destinationColumn, destinationIndex);
        }
    } else {
        return reorderPlusletAcrossSections(sourceSection, sourceColumn, sourceIndex, destinationSection,
            destinationColumn, destinationIndex);
    }
}
import { useQuery, useMutation, useQueryClient } from 'react-query';

export function useFetchPluslets(sectionId) {
    if (sectionId === undefined) throw new Error('"sectionId" argument is required to call useFetchPluslets.');

    return useQuery(['pluslets', sectionId], 
        () => fetchPluslets(sectionId), {
            select: data => data['hydra:member']
        }
    );
}

export function useFetchPluslet(plusletId) {
    if (plusletId === undefined) throw new Error('"plusletId" argument is required to call useFetchPluslet.');

    return useQuery(['pluslet', plusletId], () => fetchPluslet(plusletId));
}

export function useReorderPluslet() {
    const queryClient = useQueryClient();
    return useMutation(reorderPluslet, {
        onMutate: async plusletData => {
            await queryClient.cancelQueries(['pluslets', plusletData.sectionId]);
            
            const sourcePlusletsData = queryClient.getQueryData(['pluslets', plusletData.sectionId]);
            const sourcePluslets = [...sourcePlusletsData['hydra:member']];
            const sourceColumn = plusletData.sourceColumn;
            const sourceIndex = plusletData.sourceIndex;
            const destinationColumn = plusletData.destinationColumn;
            const destinationIndex = plusletData.destinationIndex;

            // Produce optimistic result
            if (sourceColumn === destinationColumn) {
                const columnPluslets = sourcePluslets.filter(pluslet => 
                    pluslet.pcolumn === sourceColumn);
                
                const updatedPluslets = {};

                // Move pluslet within the same column
                const [reorderedItem] = columnPluslets.splice(sourceIndex, 1);
                columnPluslets.splice(destinationIndex, 0, reorderedItem);

                // Index the updated prow
                columnPluslets.forEach((pluslet, index) => {
                    if (pluslet.prow !== index) {
                        updatedPluslets[pluslet.plusletId] = {
                            prow: index
                        };
                    }
                });
                
                // Set the updated prow
                sourcePluslets.forEach(pluslet => {
                    if (updatedPluslets[pluslet.plusletId]) {
                        pluslet.prow = updatedPluslets[pluslet.plusletId].prow;
                    }
                });
            } else {
                const sourceColumnPluslets = sourcePluslets.filter(pluslet => 
                    pluslet.pcolumn === sourceColumn);                
                
                const updatedPluslets = {};

                // Remove pluslet from source column
                const [reorderedItem] = sourceColumnPluslets.splice(sourceIndex, 1);

                // Set the updated prow for source column pluslets
                sourceColumnPluslets.forEach((pluslet, index) => {
                    if (pluslet.prow !== index) {
                        updatedPluslets[pluslet.plusletId] = {
                            prow: index,
                            pcolumn: sourceColumn
                        };
                    }
                });

                // Move pluslet to a different column
                const destinationColumnPluslets = sourcePluslets.filter(pluslet => 
                    pluslet.pcolumn === destinationColumn);

                // Add to destination column and reorder destination column pluslets 
                destinationColumnPluslets.splice(destinationIndex, 0, reorderedItem);
                destinationColumnPluslets.forEach((pluslet, index) => {
                    if (pluslet.prow !== index || pluslet.pcolumn !== destinationColumn) {
                        updatedPluslets[pluslet.plusletId] = {
                            prow: index,
                            pcolumn: destinationColumn
                        };
                    }
                });

                // Set the updated prow/pcolumn
                sourcePluslets.forEach(pluslet => {
                    if (updatedPluslets[pluslet.plusletId]) {
                        pluslet.prow = updatedPluslets[pluslet.plusletId].prow;
                        pluslet.pcolumn = updatedPluslets[pluslet.plusletId].pcolumn;
                    }
                });
            }

            // sort the source pluslets
            sourcePluslets.sort((plusletA, plusletB) => {
                return plusletA.pcolumn - plusletB.pcolumn || plusletA.prow - plusletB.prow;
            });

            // Set optimistic result to query data
            queryClient.setQueryData(['pluslets', plusletData.sectionId], {
                ...sourcePlusletsData,
                'hydra:member': sourcePluslets,
            });

            return { sourcePlusletsData };
        },
        onError: (error, plusletData, context) => {
            // Perform rollback of pluslet order mutation
            console.error(error);
            queryClient.setQueryData(['pluslets', plusletData.sectionId], context.sourcePlusletsData);
        },
        onSettled: (data, error, plusletData) => {
            // Refetch the pluslet order data
            queryClient.invalidateQueries(['pluslets', plusletData.sectionId]);
        }
    });
}

async function fetchPluslets(sectionId, filters=null) {
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

async function updatePluslet({plusletId, data}) {
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

async function reorderPlusletWithinColumn(sectionId, column, sourceIndex, destinationIndex) {
    const {'hydra:member': pluslets} = await fetchPluslets(sectionId, {
        pcolumn: column
    });
    
    // Move pluslet within the same column
    const [reorderedItem] = pluslets.splice(sourceIndex, 1);
    pluslets.splice(destinationIndex, 0, reorderedItem);

    // Perform the updating of the section index asynchronously
    return Promise.all(pluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index) {
            return fetch(`/api/pluslets/${pluslet.plusletId}`, {
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

async function reorderPlusletWithinSection(sectionId, sourceColumn, sourceIndex, destinationColumn, destinationIndex) {
    const {'hydra:member': sourcePluslets} = await fetchPluslets(sectionId, {
        pcolumn: sourceColumn
    });

    // Remove pluslet from source column
    const [reorderedItem] = sourcePluslets.splice(sourceIndex, 1);

    // Reorder source column pluslets
    await Promise.all(sourcePluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index) {
            return fetch(`/api/pluslets/${pluslet.plusletId}`, {
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

    // Move pluslet to a different column
    const {'hydra:member': destinationPluslets} = await fetchPluslets(sectionId, {
        pcolumn: destinationColumn
    });

    // Add to destination column and reorder destination column pluslets 
    destinationPluslets.splice(destinationIndex, 0, reorderedItem);
    return Promise.all(destinationPluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index || pluslet.pcolumn !== destinationColumn) {
            return fetch(`/api/pluslets/${pluslet.plusletId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    prow: index,
                    pcolumn: destinationColumn
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));
}

async function reorderPluslet({sectionId, sourceColumn, sourceIndex, destinationColumn, destinationIndex}) {
    if (sectionId === undefined) throw new Error('"sectionId" field is required to perform reorder pluslet request.');
    if (sourceColumn === undefined) throw new Error('"sourceColumn" field is required to perform reorder pluslet request.');
    if (sourceIndex === undefined) throw new Error('"sourceIndex" field is required to perform reorder pluslet request.');
    if (destinationColumn === undefined) throw new Error('"destinationColumn" field is required to perform reorder pluslet request.');
    if (destinationIndex === undefined) throw new Error('"destinationIndex" field is required to perform reorder pluslet request.');

    // Move pluslet within the same column
    if (sourceColumn === destinationColumn) {
        return reorderPlusletWithinColumn(sectionId, sourceColumn, sourceIndex, destinationIndex);
    } else {
        return reorderPlusletWithinSection(sectionId, sourceColumn, sourceIndex, destinationColumn, destinationIndex);
    }
}
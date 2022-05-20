import { useQuery, useMutation, useQueryClient } from 'react-query';
import { fetchPluslets } from './PlusletAPI';
import produce from 'immer';

export function useFetchSections(tabId) {
    if (tabId === undefined) throw new Error('"tabId" argument is required to call useFetchSections.');

    return useQuery(['sections', tabId], 
        () => fetchSections(tabId, {pagination: false}), {
            select: data => data['hydra:member'],
            staleTime: 5000
        }
    );
}

export function useFetchSection(sectionId) {
    if (sectionId === undefined) throw new Error('"sectionId" argument is required to call useFetchSection.');

    return useQuery(['section', sectionId], () => fetchSection(sectionId));
}

export function useCreateSection(tabId) {
    if (tabId === undefined) throw new Error('"tabId" field is required to call useCreateSection.');

    const queryClient = useQueryClient();
    return useMutation(createSection, {
        onMutate: async newSection => {
            await queryClient.cancelQueries(['sections', tabId]);
            const previousSectionsData = queryClient.getQueryData(['sections', tabId]);
            const optimisticResult = produce(previousSectionsData, draftData => {
                draftData['hydra:member'].push(newSection);
            });
            
            queryClient.setQueryData(['sections', tabId], optimisticResult);
            
            return { previousSectionsData };
        },
        onError: (error, newSection, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['sections', tabId], context.previousSectionsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['sections', tabId]);
        },
    });
}

export function useUpdateSection(tabId) {
    if (tabId === undefined) throw new Error('"tabId" field is required to call useUpdateSection.');

    const queryClient = useQueryClient();
    return useMutation(updateSection, {
        onMutate: async updatedSection => {
            await queryClient.cancelQueries(['sections', tabId]);
            const previousSectionsData = queryClient.getQueryData(['sections', tabId]);

            const optimisticResult = produce(previousSectionsData, draftData => {
                draftData['hydra:member'][updatedSection.sectionIndex] = updatedSection.optimisticResult;
            });
            
            queryClient.setQueryData(['sections', tabId], optimisticResult);
            return { previousSectionsData };
        },
        onError: (error, sectionData, context) => {
            // Perform rollback of section mutation
            console.error(error);
            queryClient.setQueryData(['sections', tabId], context.previousSectionsData);
        },
        onSettled: () => {
            // Refetch the section data
            queryClient.invalidateQueries(['sections', tabId]);
        },
    });
}

export function useDeleteSection(tabId) {
    if (tabId === undefined) throw new Error('"tabId" field is required to call useDeleteSection.');

    const queryClient = useQueryClient();
    return useMutation(deleteSection, {
        onMutate: async deletedSection => {
            await queryClient.cancelQueries(['sections', tabId]);
            const previousSectionsData = queryClient.getQueryData(['sections', tabId]);

            const optimisticResult = produce(previousSectionsData, draftData => {
                draftData['hydra:member'] = draftData['hydra:member'].filter((section) => section.id !== deletedSection.sectionId);
                draftData['hydra:member'].forEach((section, index) => section.sectionIndex = index);
                draftData['hydra:totalItems'] = draftData['hydra:member'].length;
            });
            
            queryClient.setQueryData(['sections', tabId], optimisticResult);
            
            return { previousSectionsData };
        },
        onError: (error, sectionData, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['sections', tabId], context.previousSectionsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['sections', tabId]);
        },
    });
}

export function useReorderSection(tabId) {
    if (tabId === undefined) throw new Error('"tabId" field is required to call useReorderSection.');

    const queryClient = useQueryClient();
    return useMutation(reorderSection, {
        onMutate: async sectionData => {
            await queryClient.cancelQueries(['sections', tabId]);
            const previousSectionsData = queryClient.getQueryData(['sections', tabId]);

            // produce optimistic result
            const optimisticResult = produce(previousSectionsData, draftData => {
                const [reorderedSection] = draftData['hydra:member'].splice(sectionData.sourceSectionIndex, 1);
                draftData['hydra:member'].splice(sectionData.destinationSectionIndex, 0, reorderedSection);
                draftData['hydra:member'].forEach((section, index) => section.sectionIndex = index);
            });

            queryClient.setQueryData(['sections', tabId], optimisticResult);
            
            return { previousSectionsData };
        },
        onError: (error, sectionsData, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['sections', tabId], context.previousSectionsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['sections', tabId]);
        },
    });
}

export function useConvertSectionLayout(sectionId) {
    if (sectionId === undefined) throw new Error('"sectionId" field is required to call useConvertSectionLayout.');

    const queryClient = useQueryClient();
    return useMutation(convertSectionLayout, {
        onMutate: async updatedSection => {
            await queryClient.cancelQueries(['pluslets', sectionId]);
            await queryClient.cancelQueries(['sections', updatedSection.tabId]);
            const previousPlusletsData = queryClient.getQueryData(['pluslets', sectionId]);
            const previousSectionsData = queryClient.getQueryData(['sections', updatedSection.tabId]);

            const plusletsOptimisticResult = produce(previousPlusletsData, draftData => {
                const oldLayout = previousSectionsData['hydra:member'][updatedSection.sectionIndex]['layout'];
                const oldLayoutSizes = oldLayout.split('-');
                const oldLayoutTotalColumns = (oldLayoutSizes.filter(layout => Number(layout) !== 0)).length;
                const newLayoutSizes = updatedSection.newLayout.split('-');
                const newLayoutTotalColumns = (newLayoutSizes.filter(layout => Number(layout) !== 0)).length;
                const newLayoutLastColumn = newLayoutTotalColumns - 1;
                const updates = {};

                if (oldLayoutTotalColumns > newLayoutTotalColumns) {
                    // If the old layout has more columns than the new layout, any excess pluslets from the old layout
                    // will join the last column of the new layout.
                    
                    // Filter pluslets with pcolumn greater than or equal to last column of the new layout then sort by pcolumn and prow
                    const columnPluslets = draftData['hydra:member'].filter(pluslet => pluslet.pcolumn >= newLayoutLastColumn)
                    .sort((plusletA, plusletB) => {
                        if (plusletA.pcolumn === plusletB.pcolumn) {
                            return plusletA.prow - plusletB.prow;
                        }
                        return plusletA.pcolumn - plusletB.pcolumn;
                    });

                    columnPluslets.forEach((pluslet, index) => {
                        if (pluslet.pcolumn !== newLayoutLastColumn || pluslet.prow !== index) {
                            updates[pluslet.id] = {
                                pcolumn: newLayoutLastColumn,
                                prow: index
                            };
                        }
                    });

                    draftData['hydra:member'].forEach(pluslet => {
                        if (updates[pluslet.id]) {
                            pluslet.pcolumn = updates[pluslet.id].pcolumn;
                            pluslet.prow = updates[pluslet.id].prow;
                        }
                    });
                }
            });

            const sectionsOptimisticResult = produce(previousSectionsData, draftData => {
                // Update Section layout property to new layout
                draftData['hydra:member'][updatedSection.sectionIndex]['layout'] = updatedSection.newLayout;
            });
            
            queryClient.setQueryData(['pluslets', sectionId], plusletsOptimisticResult);
            queryClient.setQueryData(['sections', updatedSection.tabId], sectionsOptimisticResult);

            return { previousPlusletsData, previousSectionsData };
        },
        onError: (error, sectionData, context) => {
            // Perform rollback of section and pluslets mutation
            console.error(error);
            queryClient.setQueryData(['pluslets', sectionId], context.previousPlusletsData);
            queryClient.setQueryData(['sections', sectionData.tabId], context.previousSectionsData);
        },
        onSettled: (data, error, sectionData, context) => {
            // Refetch the section and pluslets data
            queryClient.invalidateQueries(['pluslets', sectionId]);
            queryClient.invalidateQueries(['sections', sectionData.tabId]);
        },
    });
}

async function fetchSections(tabId, filters=null) {
    const data = await fetch(`/api/tabs/${tabId}/sections`
        + (filters ? '?' + new URLSearchParams(filters) : ''));
    
    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function convertSectionLayout({sectionId, newLayout}) {
    if (sectionId === undefined) throw new Error('"sectionId" field is required to perform convert section layout operation.');
    if (newLayout === undefined) throw new Error('"newLayout" field is required to perform convert section layout operation.');

    const section = await fetchSection(sectionId);
    const oldLayout = section.layout;
    const oldLayoutSizes = oldLayout.split('-');
    const oldLayoutTotalColumns = (oldLayoutSizes.filter(layout => Number(layout) !== 0)).length;
    const newLayoutSizes = newLayout.split('-');
    const newLayoutTotalColumns = (newLayoutSizes.filter(layout => Number(layout) !== 0)).length;
    const newLayoutLastColumn = newLayoutTotalColumns - 1;

    // When changing from one layout to another, if the total number of columns are the same,
    // or the new layout has more columns than the old layout then no change to the Pluslet pcolumn 
    // or prow is needed.
    // If the old layout has more columns than the new layout, any excess pluslets from the old layout
    // will join the last column of the new layout.
    if (oldLayoutTotalColumns > newLayoutTotalColumns) {
        // Fetch pluslets with pcolumn greater than or equal to last column of the new layout
        let {'hydra:member': pluslets } = await fetchPluslets(sectionId, {
            'pcolumn[gte]': newLayoutLastColumn
        });
        
        // Change the pcolumn and prow indexes for Pluslet to reflect new layout
        await Promise.all(pluslets.map(async (pluslet, row) => {
            if (pluslet.pcolumn !== newLayoutLastColumn || pluslet.prow !== row) {
                return fetch(`/api/pluslets/${pluslet.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/ld+json',
                    },
                    body: JSON.stringify({
                        pcolumn: newLayoutLastColumn,
                        prow: row
                    })
                }).catch(error => {
                    console.error(error);
                    throw new Error(error);
                });
            }
        }));
    }

    // Update Section layout property
    return updateSection({
        sectionId: sectionId,
        data: {
            layout: newLayout
        }
    });
}

async function fetchSection(sectionId) {
    const data = await fetch(`/api/sections/${sectionId}`);

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function createSection(initialSectionData) {
    const sectionReq = await fetch('/api/sections', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/ld+json',
        },
        body: JSON.stringify(initialSectionData)
    });

    if (!sectionReq.ok) {
        throw new Error(sectionReq.status + ' ' + sectionReq.statusText);
    }

    return sectionReq.json();
}

async function updateSection({sectionId, data}) {
    if (sectionId === undefined) throw new Error('"sectionId" field is required to perform update section request.');
    if (data === undefined) throw new Error('"data" field is required to perform update section request');

    const req = await fetch(`/api/sections/${sectionId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/ld+json',
        },
        body: JSON.stringify(data)
    });

    if (!req.ok) {
        throw new Error(req.status + ' ' + req.statusText);
    }

    return req.json();
}

async function deleteSection({ sectionId }) {
    if (sectionId === undefined) throw new Error('"sectionId" field is required to perform delete section request.');

    const sectionToDelete = await fetchSection(sectionId);
    const tabId = sectionToDelete['tab'].split("/").pop();
    const {'hydra:member': sections } = await fetchSections(tabId, {pagination: false});
    const newSections = [...sections];
   
    // update the section index
    newSections.splice(sectionToDelete.sectionIndex, 1);
    await Promise.all(newSections.map(async (section, index) => {
        if (section.sectionIndex !== index) {
            return fetch(`/api/sections/${section.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/ld+json',
                },
                body: JSON.stringify({
                    sectionIndex: index
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));

    // delete the section
    const req = await fetch(`/api/sections/${sectionId}`, {
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

async function reorderSection({ tabId, sourceSectionIndex, destinationSectionIndex }) {
    if (tabId === undefined) throw new Error('"tabId" field is required to perform reorder section request.');
    if (sourceSectionIndex === undefined) throw new Error('"sourceSectionIndex" field is required to perform reorder section request');
    if (destinationSectionIndex === undefined) throw new Error('"destinationSectionIndex" field is required to perform reorder section request.');
    
    // fetch current sections
    const {'hydra:member': sections} = await fetchSections(tabId, {pagination: false});

    // copy existing sections
    const newSections = [...sections];

    // reorder sections
    const [reorderedItem] = newSections.splice(sourceSectionIndex, 1);
    newSections.splice(destinationSectionIndex, 0, reorderedItem);

    // perform the updating of the section index asynchronously
    return Promise.all(newSections.map(async (section, index) => {
        if (newSections[index].sectionIndex !== index) {
            return fetch(`/api/sections/${section.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/ld+json',
                },
                body: JSON.stringify({
                    sectionIndex: index
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));
}
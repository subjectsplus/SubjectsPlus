import { fetchPluslets } from './PlusletAPI';
import { GuideSectionType, PlusletType } from '@shared/types/guide_types';
import { ReorderSectionMutationArgs, UpdateSectionMutationArgs, DeleteSectionMutationArgs, ConvertSectionLayoutMutationArgs } from '@shared/types/guide_mutation_types';

export const fetchSections = async (tabUUID: string, filters: Record<string, any>|null = null) => {
    const data = await fetch(`/api/tabs/${tabUUID}/sections`
        + (filters ? '?' + new URLSearchParams(filters) : ''));
    
    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const fetchSection = async (sectionUUID: string): Promise<GuideSectionType> => {
    const data = await fetch(`/api/sections/${sectionUUID}`);

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const createSection = async (initialSectionData: Record<string, any>): Promise<GuideSectionType> => {
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

export const updateSection = async ({sectionUUID, data}: UpdateSectionMutationArgs) => {
    const req = await fetch(`/api/sections/${sectionUUID}`, {
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

export const deleteSection = async ({ sectionUUID }: DeleteSectionMutationArgs) => {
    const sectionToDelete = await fetchSection(sectionUUID);
    const tabUUID = sectionToDelete['tab'].split("/").pop();
    
    if (tabUUID) {
        const {'hydra:member': sections }:{'hydra:member': GuideSectionType[]} = await fetchSections(tabUUID, {pagination: false});
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
    }

    // delete the section
    const req = await fetch(`/api/sections/${sectionUUID}`, {
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

export const reorderSection = async ({ tabUUID, sourceSectionIndex, destinationSectionIndex }: ReorderSectionMutationArgs) => {
    // fetch current sections
    const {'hydra:member': sections}: {'hydra:member': GuideSectionType[]} = await fetchSections(tabUUID, {pagination: false});

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

export const convertSectionLayout = async ({sectionUUID, newLayout}: ConvertSectionLayoutMutationArgs) => {
    const section = await fetchSection(sectionUUID);
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
        let {'hydra:member': pluslets } = await fetchPluslets(sectionUUID, {
            'pcolumn[gte]': newLayoutLastColumn
        });
        
        // Change the pcolumn and prow indexes for Pluslet to reflect new layout
        await Promise.all(pluslets.map(async (pluslet: PlusletType, row: number) => {
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
        sectionUUID: sectionUUID,
        data: {
            layout: newLayout
        }
    });
}
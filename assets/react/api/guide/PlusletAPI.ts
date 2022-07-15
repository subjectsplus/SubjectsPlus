import { PlusletType } from '@shared/types/guide_types';
import { UpdatePlusletMutationArgs, ReorderPlusletMutationArgs, DeletePlusletMutationArgs } from '@shared/types/guide_mutation_types';

export const fetchPluslets = async (sectionUUID: string, filters: Record<string, any>|null = null): Promise<PlusletType[]> => {
    const data = await fetch(`/api/sections/${sectionUUID}/pluslets`
        + (filters ? '?' + new URLSearchParams(filters) : ''), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const fetchPluslet = async (plusletUUID: string): Promise<PlusletType> => {
    const data = await fetch(`/api/pluslets/${plusletUUID}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const createPluslet = async (initialPlusletData: Record<string, any>): Promise<PlusletType> => {
    const plusletReq = await fetch('/api/pluslets', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/ld+json',
        },
        body: JSON.stringify(initialPlusletData)
    });

    if (!plusletReq.ok) {
        throw new Error(plusletReq.status + ' ' + plusletReq.statusText);
    }

    return plusletReq.json();
}

export const updatePluslet = async ({ plusletUUID, data }: UpdatePlusletMutationArgs) => {
    const req = await fetch(`/api/pluslets/${plusletUUID}`, {
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

export const deletePluslet = async ({ plusletUUID }: DeletePlusletMutationArgs) => {
    const plusletToDelete = await fetchPluslet(plusletUUID);
    const sectionUUID = plusletToDelete['section'].split("/").pop();

    if (sectionUUID) {
        const pluslets = await fetchPluslets(sectionUUID, {
            pagination: false,
            pcolumn: plusletToDelete['pcolumn']
        });
        const newPluslets = [...pluslets];
    
        // update the row index of the pluslets within the column
        newPluslets.splice(plusletToDelete.prow, 1);
        await Promise.all(newPluslets.map(async (pluslet, index) => {
            if (pluslet.prow !== index) {
                return fetch(`/api/pluslets/${pluslet.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/ld+json',
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

    // delete the section
    const req = await fetch(`/api/pluslets/${plusletUUID}`, {
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

export const reorderPlusletWithinColumn = async (sectionUUID: string, column: number, sourceIndex: number, destinationIndex: number) => {
    const pluslets = await fetchPluslets(sectionUUID, {
        pcolumn: column,
        pagination: false
    });

    // Move pluslet within the same column
    const [reorderedPluslet] = pluslets.splice(sourceIndex, 1);

    if (!reorderedPluslet) throw new Error('Failed to find source pluslet to reorder.');

    pluslets.splice(destinationIndex, 0, reorderedPluslet);

    // Perform the updating of the section index asynchronously
    return Promise.all(pluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index) {
            return fetch(`/api/pluslets/${pluslet.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/ld+json',
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

export const reorderPlusletAcrossSections = async (sourceSection: string, sourceColumn: number, sourceIndex: number, destinationSection: string,
    destinationColumn: number, destinationIndex: number) => {
    const sourceColumnPluslets = await fetchPluslets(sourceSection, {
        pcolumn: sourceColumn,
        pagination: false
    });

    // Remove pluslet from source column
    const [reorderedPluslet] = sourceColumnPluslets.splice(sourceIndex, 1);

    // Note: Issue occurs when dragging multiple pluslets consistently before
    // the mutation can "catch up", possibly need some sort of limiter, or if
    // too many requests at once, force a loader screen to catch up
    if (!reorderedPluslet) throw new Error('Failed to find source pluslet to reorder.');

    // Reorder source column pluslets
    await Promise.all(sourceColumnPluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index) {
            return fetch(`/api/pluslets/${pluslet.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/ld+json',
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
    const destinationColumnPluslets = await fetchPluslets(destinationSection, {
        pcolumn: destinationColumn,
        pagination: false
    });

    // Add to destination column and reorder destination column pluslets
    destinationColumnPluslets.splice(destinationIndex, 0, reorderedPluslet);
    return Promise.all(destinationColumnPluslets.map(async (pluslet, index) => {
        if (pluslet.prow !== index || pluslet.pcolumn !== destinationColumn
            || pluslet.id === reorderedPluslet.id) {
            return fetch(`/api/pluslets/${pluslet.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/ld+json',
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

export const reorderPlusletWithinSection = (sectionUUID: string, sourceColumn: number, sourceIndex: number, destinationColumn: number, destinationIndex: number) => {
    return reorderPlusletAcrossSections(sectionUUID, sourceColumn, sourceIndex, sectionUUID, destinationColumn, destinationIndex);
}

export const reorderPluslet = ({ sourceSection, sourceColumn, sourceIndex, destinationSection, destinationColumn, destinationIndex }: ReorderPlusletMutationArgs) => {
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
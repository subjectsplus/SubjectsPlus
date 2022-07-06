import { GuideTabType } from '@shared/types/guide_types';
import { UpdateTabMutationArgs, DeleteTabMutationArgs } from '@shared/types/guide_mutation_types';

export const fetchTabs = async (subjectId: number, filters: Record<string, any>|null = null) => {
    const data = await fetch(`/api/subjects/${subjectId}/tabs`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const fetchTab = async (tabUUID: string): Promise<GuideTabType> => {
    const data = await fetch(`/api/tabs/${tabUUID}`);

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const createTab = async (initialTabData: Record<string, any>) => {
    const tabReq = await fetch('/api/tabs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/ld+json',
        },
        body: JSON.stringify(initialTabData)
    });

    if (!tabReq.ok) {
        throw new Error(tabReq.status + ' ' + tabReq.statusText);
    }

    const tabData = await tabReq.json();

    // Create new default section assigned to tab
    const sectionReq = await fetch('/api/sections', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/ld+json',
        },
        body: JSON.stringify({
            tab: `/api/tabs/${tabData.id}`
        })
    });

    if (!sectionReq.ok) {
        throw new Error(sectionReq.status + ' ' + sectionReq.statusText);
    }

    return sectionReq.json();
}
    
export const updateTab = async ({tabUUID, data}: UpdateTabMutationArgs) => {
    const req = await fetch(`/api/tabs/${tabUUID}`, {
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

export const deleteTab = async ({ tabUUID }: DeleteTabMutationArgs) => {
    const tabToDelete = await fetchTab(tabUUID);
    const subjectId = Number(tabToDelete['subject'].split("/").pop());
    const {'hydra:member': tabs }: {'hydra:member': GuideTabType[]} = await fetchTabs(subjectId, {pagination: false});
    const newTabs = [...tabs];
   
    // update the tab index
    newTabs.splice(tabToDelete.tabIndex, 1);
    await Promise.all(newTabs.map(async (tab, index) => {
        if (tab.tabIndex !== index) {
            return fetch(`/api/tabs/${tab.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/ld+json',
                },
                body: JSON.stringify({
                    tabIndex: index
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));

    // delete the tab
    const req = await fetch(`/api/tabs/${tabUUID}`, {
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

export const reorderTab = async ({subjectId, sourceTabIndex, destinationTabIndex}: {subjectId: number, sourceTabIndex: number, destinationTabIndex: number}) => {
    // fetch current tabs
    const {'hydra:member': tabs}: {'hydra:member': GuideTabType[]} = await fetchTabs(subjectId, {pagination: false});

    // copy existing tabs
    const newTabs = [...tabs];

    // reorder tabs
    const [reorderedItem] = newTabs.splice(sourceTabIndex, 1);
    newTabs.splice(destinationTabIndex, 0, reorderedItem);

    // perform the updating of the tab index asynchronously
    return Promise.all(newTabs.map(async (tab, index) => {
        if (newTabs[index].tabIndex !== index) {
            return fetch(`/api/tabs/${tab.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/ld+json',
                },
                body: JSON.stringify({
                    tabIndex: index
                })
            }).catch(error => {
                console.error(error);
                throw new Error(error);
            });
        }
    }));
}
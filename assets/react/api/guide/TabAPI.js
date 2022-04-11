import { useQuery, useMutation, useQueryClient } from 'react-query';

export function useFetchTabs(subjectId, enabled=true) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to call useFetchTabs.');

    return useQuery(['tabs', subjectId], 
        () => fetchTabs(subjectId, {pagination: false}), {
            select: data => data['hydra:member'],
            staleTime: 5000,
            enabled: enabled
        }
    );
}

export function useFetchTab(tabId, enabled=true) {
    if (tabId === undefined) throw new Error('"tabId" field is required to call useFetchTab');

    return useQuery(['tab', tabId], () => fetchTab(tabId), { enabled: enabled });
}

export function useCreateTab(subjectId) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to call useCreateTab.');

    const queryClient = useQueryClient();
    return useMutation(createTab, {
        onMutate: async newTab => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData(['tabs', subjectId]);

            queryClient.setQueryData(['tabs', subjectId], {
                ...previousTabsData,
                'hydra:member': [...previousTabsData['hydra:member'], newTab],
            });
            
            return { previousTabsData };
        },
        onError: (error, newTab, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['tabs', subjectId], context.previousTabsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['tabs', subjectId]);
        },
    });
}

export function useUpdateTab(subjectId) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to call useUpdateTab.');

    const queryClient = useQueryClient();
    return useMutation(updateTab, {
        onMutate: async updatedTab => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData(['tabs', subjectId]);

            const optimisticResult = {...previousTabsData};
            optimisticResult['hydra:member'][updatedTab.tabIndex] = updatedTab.optimisticResult;
            
            console.log('optimisticResult', optimisticResult);

            queryClient.setQueryData(['tabs', subjectId], optimisticResult);
            return { previousTabsData };
        },
        onError: (error, tabData, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['tabs', subjectId], context.previousTabsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['tabs', subjectId]);
        },
    });
}

export function useDeleteTab(subjectId) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to call useDeleteTab.');

    const queryClient = useQueryClient();
    return useMutation(deleteTab, {
        onMutate: async deletedTab => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData(['tabs', subjectId]);
            
            const optimisticResult = [...previousTabsData['hydra:member']];
            optimisticResult.splice(deletedTab.tabIndex, 1);
            optimisticResult.forEach((tab, index) => tab.tabIndex = index);

            queryClient.setQueryData(['tabs', subjectId], {
                ...previousTabsData,
                'hydra:member': optimisticResult
            });
            
            return { previousTabsData };
        },
        onError: (error, tabData, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['tabs', subjectId], context.previousTabsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['tabs', subjectId]);
        },
    });
}

export function useReorderTab(subjectId) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to call useReorderTab.');

    const queryClient = useQueryClient();
    return useMutation(reorderTab, {
        onMutate: async tabData => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData(['tabs', subjectId]);

            // produce optimistic result
            const newTabs = [...previousTabsData['hydra:member']];

            // reorder tabs
            const [reorderedItem] = newTabs.splice(tabData.sourceTabIndex, 1);
            newTabs.splice(tabData.destinationTabIndex, 0, reorderedItem);
        
            // set the updated tab index
            newTabs.forEach((tab, index) => {
                tab.tabIndex = index;
            });

            queryClient.setQueryData(['tabs', subjectId], {
                ...previousTabsData,
                'hydra:member': newTabs,
            });
            
            return { previousTabsData };
        },
        onError: (error, tabData, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['tabs', subjectId], context.previousTabsData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['tabs', subjectId]);
        },
    });
}

async function fetchTabs(subjectId, filters=null) {
    const data = await fetch(`/api/subjects/${subjectId}/tabs`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function fetchTab(tabId) {
    const data = await fetch(`/api/tabs/${tabId}`);

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function createTab(initialTabData) {
    const tabReq = await fetch('/api/tabs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
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
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            tab: `/api/tabs/${tabData.tabId}`
        })
    });

    if (!sectionReq.ok) {
        throw new Error(sectionReq.status + ' ' + sectionReq.statusText);
    }

    return sectionReq.json();
}
    
async function updateTab({tabId, data}) {
    if (tabId === undefined) throw new Error('"tabId" field is required to perform update tab request.');
    if (data === undefined) throw new Error('"data" field is required to perform update tab request');

    const req = await fetch(`/api/tabs/${tabId}`, {
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

async function deleteTab(tabId) {
    if (tabId === undefined) throw new Error('"tabId" field is required to perform delete tab request.');

    const tabToDelete = await fetchTab(tabId);
    const subjectId = tabToDelete['subject'].split("/").pop();
    const {'hydra:member': tabs } = await fetchTabs(subjectId, {pagination: false});
    const newTabs = [...tabs];
   
    // update the tab index
    newTabs.splice(tabToDelete.tabIndex, 1);
    await Promise.all(newTabs.map((tab, index) => {
        if (tab.tabIndex !== index) {
            return fetch(`/api/tabs/${tab.tabId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
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
    const req = await fetch(`/api/tabs/${tabId}`, {
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

async function reorderTab({subjectId, sourceTabIndex, destinationTabIndex}) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to perform reorder tab request.');
    if (sourceTabIndex === undefined) throw new Error('"sourceTabIndex" field is required to perform reorder tab request');
    if (destinationTabIndex === undefined) throw new Error('"destinationTabIndex" field is required to perform reorder tab request.');
    
    // fetch current tabs
    const {'hydra:member': tabs} = await fetchTabs(subjectId, {pagination: false});

    // copy existing tabs
    const newTabs = [...tabs];

    // reorder tabs
    const [reorderedItem] = newTabs.splice(sourceTabIndex, 1);
    newTabs.splice(destinationTabIndex, 0, reorderedItem);

    // perform the updating of the tab index asynchronously
    return Promise.all(newTabs.map((tab, index) => {
        if (newTabs[index].tabIndex !== index) {
            return fetch(`/api/tabs/${tab.tabId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
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
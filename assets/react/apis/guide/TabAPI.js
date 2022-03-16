import { useQuery, useMutation, useQueryClient } from 'react-query';

export function useFetchTabs(subjectId) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to call useFetchTabs.');

    return useQuery(['tabs', subjectId], 
        () => fetchTabs(subjectId), {
            select: data => data['hydra:member']
        }
    );
}

export function useFetchTab(tabId) {
    if (tabId === undefined) throw new Error('"tabId" field is required to call useFetchTab');

    return useQuery(['tab', tabId], () => fetchTab(tabId));
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
            
            const optimisticResult = {...previousTabsData};
            optimisticResult['hydra:member'].splice(deletedTab.tabIndex, 1);
            optimisticResult['hydra:member'].forEach((tab, index) => tab.tabIndex = index);

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

async function fetchTabs(subjectId) {
    const data = await fetch(`/api/subjects/${subjectId}/tabs`);
    return data.json();
}

async function fetchTab(tabId) {
    const data = await fetch(`/api/tabs/${tabId}`);
    return data.json();
}

async function createTab(initialTabData) {
    const req = await fetch('/api/tabs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(initialTabData)
    });
    return req.json();
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
    return req.json();
}

async function deleteTab(tabId) {
    if (tabId === undefined) throw new Error('"tabId" field is required to perform delete tab request.');

    const tabToDelete = await fetchTab(tabId);
    const subjectId = tabToDelete['subject'].split("/").pop();
    const {'hydra:member': tabs } = await fetchTabs(subjectId);
    const newTabs = [...tabs];
   
    // update the tab index
    console.log('tab index of tab to delete: ', tabToDelete.tabIndex);
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
    
    return req.json();
}

async function reorderTab({subjectId, sourceTabIndex, destinationTabIndex}) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to perform reorder tab request.');
    if (sourceTabIndex === undefined) throw new Error('"sourceTabIndex" field is required to perform update tab request');
    if (destinationTabIndex === undefined) throw new Error('"destinationTabIndex" field is required to perform update tab request.');
    
    // fetch current tabs
    const {'hydra:member': tabs} = await fetchTabs(subjectId);

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
            });
        }
    }));
}
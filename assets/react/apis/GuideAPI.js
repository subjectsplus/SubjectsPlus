import { useQuery, useMutation, useQueryClient } from 'react-query';

export function useReorderTab(subjectId) {
    const queryClient = useQueryClient();
    return useMutation(reorderTab, {
        onMutate: async tabData => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabData = queryClient.getQueryData(['tabs', subjectId]);

            queryClient.setQueryData(['tabs', subjectId], {
                ...previousTabData,
                'hydra:member': tabData.optimisticResult,
            });
            
            return { previousTabData };
        },
        onError: (error, tabData, context) => {
            // Perform rollback of tab mutation
            console.error(error);
            queryClient.setQueryData(['tabs', subjectId], context.previousTabData);
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['tabs', subjectId]);
        },
    });
}

export function useFetchTabs(subjectId) {
    return useQuery(['tabs', subjectId], 
    () => fetchTabs(subjectId), {
        select: data => data['hydra:member']
    }
);
}

async function fetchTabs(subjectId) {
    const data = await fetch(`/api/subjects/${subjectId}/tabs`);
    return data.json();
}

async function createTab(subjectId, initialTabData) {
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
    if (!tabId) throw new Error('"tabId" field is required to perform update tab request.');
    if (!data) throw new Error('"data" field is required to perform update tab request');

    const req = await fetch(`/api/tabs/${tabId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    });
    return req.json();
}

async function reorderTab({subjectId, sourceTabIndex, destinationTabIndex}) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to perform reorder tab request.');
    if (sourceTabIndex === undefined) throw new Error('"sourceTabIndex" field is required to perform update tab request');
    if (destinationTabIndex === undefined) throw new Error('"destinationTabIndex" field is required to perform update tab request.');
    
    // fetch current tabs
    const tabReq = await fetchTabs(subjectId);
    const tabs = tabReq['hydra:member'];

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
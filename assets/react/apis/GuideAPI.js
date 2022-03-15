
export default class GuideAPI {

    static async fetchTabs(subjectId) {
        const data = await fetch(`/api/subjects/${subjectId}/tabs`);
        return data.json();
    }
    
    static async updateTab({tabId, data}) {
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

    static async reorderTab({subjectId, sourceTabIndex, destinationTabIndex}) {
        if (subjectId === undefined) throw new Error('"subjectId" field is required to perform reorder tab request.');
        if (sourceTabIndex === undefined) throw new Error('"sourceTabIndex" field is required to perform update tab request');
        if (destinationTabIndex === undefined) throw new Error('"destinationTabIndex" field is required to perform update tab request.');
        
        // fetch current tabs
        const tabReq = await GuideAPI.fetchTabs(subjectId);
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
}
import { useQuery } from 'react-query';

export function useFetchSections(tabId) {
    if (tabId === undefined) throw new Error('"tabId" argument is required to call useFetchSections.');

    return useQuery(['sections', tabId], 
        () => fetchSections(tabId), {
            select: data => data['hydra:member']
        }
    );
}

export function useFetchSection(sectionId) {
    if (sectionId === undefined) throw new Error('"sectionId" argument is required to call useFetchSection.');

    return useQuery(['section', sectionId], () => fetchSection(sectionId));
}

async function fetchSections(tabId) {
    const data = await fetch(`/api/tabs/${tabId}/sections`);
    return data.json();
}

async function fetchSection(sectionId) {
    const data = await fetch(`/api/sections/${sectionId}`);
    return data.json();
}
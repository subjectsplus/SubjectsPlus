import { useQuery } from 'react-query';

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

async function fetchPluslets(sectionId) {
    const data = await fetch(`/api/sections/${sectionId}/pluslets`);
    return data.json();
}

async function fetchPluslet(plusletId) {
    const data = await fetch(`/api/pluslets/${plusletId}`);
    return data.json();
}
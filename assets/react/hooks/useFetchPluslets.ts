import { useQuery } from 'react-query';
import { fetchPluslets } from '@api/guide/PlusletAPI';

export const useFetchPluslets = (sectionUUID: string) => {
    return useQuery<Record<string, any>, Error>(['pluslets', sectionUUID],
        () => fetchPluslets(sectionUUID, { pagination: false }), {
        select: data => data['hydra:member'],
        staleTime: 5000
    });
}
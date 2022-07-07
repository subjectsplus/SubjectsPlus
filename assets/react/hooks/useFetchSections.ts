import { useQuery } from 'react-query';
import { fetchSections } from '@api/guide/SectionAPI';

export const useFetchSections = (tabUUID: string) => {
    return useQuery<Record<string, any>, Error>(['sections', tabUUID], 
        () => fetchSections(tabUUID, {pagination: false}), {
            select: data => data['hydra:member'],
            staleTime: 5000
        }
    );
}
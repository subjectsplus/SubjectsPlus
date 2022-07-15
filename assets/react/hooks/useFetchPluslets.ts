import { useQuery } from 'react-query';
import { fetchPluslets } from '@api/guide/PlusletAPI';
import { PlusletType } from '@shared/types/guide_types';

export const useFetchPluslets = (sectionUUID: string) => {
    return useQuery<PlusletType[], Error>(['pluslets', sectionUUID],
        () => fetchPluslets(sectionUUID, { pagination: false }), {
            staleTime: 5000
    });
}
import { useQuery } from 'react-query';
import { fetchSections } from '@api/guide/SectionAPI';
import { GuideSectionType } from '@shared/types/guide_types';

export const useFetchSections = (tabUUID: string) => {
    return useQuery<GuideSectionType[], Error>(['sections', tabUUID], 
        () => fetchSections(tabUUID, {pagination: false}), {
            staleTime: 5000
        }
    );
}
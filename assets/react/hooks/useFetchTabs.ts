import { useQuery } from 'react-query';
import { GuideTabType } from '@shared/types/guide_types';
import { fetchTabs } from '@api/guide/TabAPI';

export const useFetchTabs = (subjectId: number, enabled: boolean = true) => {
    return useQuery<GuideTabType[], Error>(['tabs', subjectId], 
        () => fetchTabs(subjectId, {pagination: false}), {
            staleTime: 5000,
            enabled: enabled
        }
    );
}
import { useQuery } from 'react-query';
import { GuideTabType } from '@shared/types/guide_types';
import { fetchTabs } from '@api/guide/TabAPI';

export const useFetchTabs = (subjectId: number, enabled: boolean = true) => {
    return useQuery<Record<string, any>, Error>(['tabs', subjectId], 
        () => fetchTabs(subjectId, {pagination: false}), {
            select: data => (data['hydra:member'] as GuideTabType[]),
            staleTime: 5000,
            enabled: enabled
        }
    );
}
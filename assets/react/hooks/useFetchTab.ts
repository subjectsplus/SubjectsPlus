import { useQuery } from 'react-query';
import { fetchTab } from '@api/guide/TabAPI';
import { GuideTabType } from '@shared/types/guide_types';

export const useFetchTab = (tabUUID: string, enabled: boolean = true) => {
    return useQuery<GuideTabType, Error>(['tab', tabUUID], () => fetchTab(tabUUID), { enabled: enabled });
}
import { useQuery } from 'react-query';
import { fetchTab } from '@api/guide/TabAPI';

export const useFetchTab = (tabUUID: string, enabled: boolean = true) => {
    return useQuery<Record<string, any>, Error>(['tab', tabUUID], () => fetchTab(tabUUID), { enabled: enabled });
}
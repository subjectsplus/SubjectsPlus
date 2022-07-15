import { useQuery } from 'react-query';
import { fetchPluslet } from '@api/guide/PlusletAPI';
import { PlusletType } from '@shared/types/guide_types';

export function useFetchPluslet(plusletUUID: string) {
    return useQuery<PlusletType, Error>(['pluslet', plusletUUID], () => fetchPluslet(plusletUUID));
}
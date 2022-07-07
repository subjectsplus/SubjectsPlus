import { useQuery } from 'react-query';
import { fetchSection } from '@api/guide/SectionAPI';

export const useFetchSection = (sectionUUID: string) => {
    return useQuery<Record<string, any>, Error>(['section', sectionUUID], () => fetchSection(sectionUUID));
}
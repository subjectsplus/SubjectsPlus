import { useQuery } from 'react-query';
import { fetchSection } from '@api/guide/SectionAPI';
import { GuideSectionType } from '@shared/types/guide_types';

export const useFetchSection = (sectionUUID: string) => {
    return useQuery<GuideSectionType, Error>(['section', sectionUUID], () => fetchSection(sectionUUID));
}
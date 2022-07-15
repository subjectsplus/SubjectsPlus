import { useQuery } from 'react-query';
import { GuideType } from '@shared/types/guide_types';
import { fetchGuide } from '@api/guide/GuideAPI';

export const useFetchGuide = (subjectId: number, filters = null) => {
    return useQuery<GuideType, Error>(['guide', subjectId], () => fetchGuide(subjectId, filters));
}
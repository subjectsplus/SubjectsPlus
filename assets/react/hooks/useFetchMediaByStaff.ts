import { useQuery } from 'react-query';
import { MediaType } from '@shared/types/media_types';
import { fetchMedia } from '@api/media/MediaAPI';

export const useFetchMediaByStaff = (staffId: number) => {
    if (staffId === undefined) throw new Error('"staffId" field is required to call useFetchMediaByStaff.');

    return useQuery<Record<string, any>, Error>(['media', staffId], 
        () => fetchMedia({
            staff: staffId
        }), {
            select: data => (data['hydra:member'] as MediaType[]),
            staleTime: 5000,
        }
    );
}
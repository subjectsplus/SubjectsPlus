
import { useQuery } from 'react-query';

export function useFetchMediaByStaff(staffId) {
    if (staffId === undefined) throw new Error('"staffId" field is required to call useFetchMediaByStaff.');

    return useQuery(['media', staffId], 
        () => fetchMedia({
            staff: '/api/staff/' + staffId
        }), {
            select: data => data['hydra:member'],
            staleTime: 5000,
        }
    );
}

async function fetchMedia(filters = null) {
    const data = await fetch(`/api/media`
    + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}
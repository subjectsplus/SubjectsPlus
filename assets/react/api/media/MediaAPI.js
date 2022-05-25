
import { useQuery } from 'react-query';

export function useFetchMediaByStaff(staffId) {
    if (staffId === undefined) throw new Error('"staffId" field is required to call useFetchMediaByStaff.');

    return useQuery(['media', staffId], 
        () => fetchMedia({
            staff: staffId
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

export async function createMedia(initialMediaData) {
    if (!('file' in initialMediaData)) throw new Error('"file" field is required to perform create media request');

    const form = new FormData();
    for (const key of Object.keys(initialMediaData)) {
        form.append(key, initialMediaData[key]);
    }
    
    const mediaReq = await fetch('/api/media', {
        method: 'POST',
        body: form
    });

    if (!mediaReq.ok) {
        throw new Error(mediaReq.status + ' ' + mediaReq.statusText);
    }

    return mediaReq.json();
}
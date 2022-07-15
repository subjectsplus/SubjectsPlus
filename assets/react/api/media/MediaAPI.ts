import { MediaType } from '@shared/types/media_types';

export const fetchMedia = async (filters: Record<string, any>|null  = null): Promise<MediaType> => {
    const data = await fetch(`/api/media`
    + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const createMedia = async (initialMediaData: Record<string, any>): Promise<MediaType> => {
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
import { GuideType } from '@shared/types/guide_types';

export const fetchGuides = async (filters:Record<string, string>|null = null): Promise<GuideType> => {
    const data = await fetch(`/api/subjects/`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const fetchGuide = async (subjectId: number, filters:Record<string, string>|null = null): Promise<GuideType> => {
    const data = await fetch(`/api/subjects/${subjectId}`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const updateGuide = async ({ subjectId, data }: {subjectId: number, data: GuideType}): Promise<GuideType> => {
    const req = await fetch(`/api/subjects/${subjectId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/ld+json',
        },
        body: JSON.stringify(data)
    });

    if (!req.ok) {
        throw new Error(req.status + ' ' + req.statusText);
    }

    return req.json();
}
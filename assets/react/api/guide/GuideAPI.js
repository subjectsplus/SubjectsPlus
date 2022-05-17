import { useQuery, useMutation, useQueryClient } from 'react-query';
import produce from 'immer';

export function useFetchGuide(subjectId, filters = null) {
    if (subjectId === undefined) throw new Error('"subjectId" argument is required to call useFetchGuide.');

    return useQuery(['guide', subjectId], () => fetchGuide(subjectId, filters));
}

export function useUpdateGuide(subjectId) {
    if (subjectId === undefined) throw new Error('"subjectiD" field is required to call useUpdateGuide.');

    const queryClient = useQueryClient();
    return useMutation(updateGuide, {
        onMutate: async updatedGuide => {
            await queryClient.cancelQueries(['guide', subjectId]);
            const previousGuideData = queryClient.getQueryData(['guide', subjectId]);
            
            const optimisticResult = produce(previousGuideData, draftData => {
                Object.keys(updatedGuide.data).map(key => {
                    draftData[key] = updatedGuide.data[key];
                });
            });

            queryClient.setQueryData(['guide', subjectId], optimisticResult);
            return { previousGuideData };
        },
        onError: (error, guideData, context) => {
            // Perform rollback of guide mutation
            console.error(error);
            queryClient.setQueryData(['guide', subjectId], context.previousGuideData);
        },
        onSettled: () => {
            // Refetch the guide data
            queryClient.invalidateQueries(['guide', subjectId]);
        },
    });
}

export async function fetchGuides(filters=null) {
    const data = await fetch(`/api/subjects/`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function fetchGuide(subjectId, filters=null) {
    const data = await fetch(`/api/subjects/${subjectId}`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function updateGuide({ subjectId, data }) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to perform update guide request.');
    if (data === undefined) throw new Error('"data" field is required to perform update guide request');

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
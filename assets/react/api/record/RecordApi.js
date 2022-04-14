import { useQuery, useMutation, useQueryClient } from 'react-query';


export function useFetchRecords(letter, azlist, page=1) {
    return useQuery(['records', letter],
        () => fetchRecords({
            letter: letter,
            'location.eresDisplay': 'Y',
            page: page
        }), {
        select: data => data['hydra:member']
    }
    );
}

async function fetchRecords(filters=null) {
    const data = await fetch(`/api/titles`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

async function fetchRecord(titleId) {
    const data = await fetch(`/api/titles/${titleId}/locations`);

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}
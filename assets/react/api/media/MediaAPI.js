
export function fetchMedia(filters = null) {
    const data = await fetch(`/api/media`
    + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}
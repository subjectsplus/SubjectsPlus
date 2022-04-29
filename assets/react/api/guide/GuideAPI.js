
export async function fetchGuides(filters=null) {
    const data = await fetch(`/api/subjects/`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export async function fetchGuide(subjectId, filters=null) {
    const data = await fetch(`/api/subjects/${subjectId}`
        + (filters ? '?' + new URLSearchParams(filters) : ''));

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}
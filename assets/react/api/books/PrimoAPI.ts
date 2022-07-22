
// temporary placeholders, will be config values
const apiLink = 'https://api-na.hosted.exlibrisgroup.com/primo/v1/search'; 
const apiKey = 'l8xxd98bb8de92664a2291c5c1ec642d2754';

export const getPrimoBookByISBN = async (isbn: string): Promise<Record<string, any>> => {
    const link = apiLink + '?' + (new URLSearchParams({
        vid: 'uml_new',
        tab: 'default_tab',
        scope: 'default_scope',
        q: 'any,contains,' + isbn,
        lang: 'eng',
        offset: '0',
        limit: '10',
        sort: 'rank',
        pcAvailability: 'true',
        getMore: '0',
        conVoc: 'true',
        inst: '01UOML',
        skipDelivery: 'true',
        disableSplitFacets: 'true',
        apikey: apiKey
    }));

    const data = await fetch(link);

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}
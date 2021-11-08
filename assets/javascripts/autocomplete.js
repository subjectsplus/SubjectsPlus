import accessibleAutocomplete from 'accessible-autocomplete';

export function createAutocomplete(
    selector,
    id,
    apiEndpoint,
    name,
    templates,
    onConfirmCallback = (q, p) => {}
) {
    accessibleAutocomplete({
        element: document.querySelector(selector),
        id: id,
        showNoOptionsFound: false,
        source: getSuggestCallback(apiEndpoint),
        name: name,
        templates: templates,
        onConfirm: onConfirmCallback
      })
}

function getSuggestCallback(apiEndpoint) {
    return function suggest (query, populateResults) {
        fetch(apiEndpoint+query)
        .then(response => response.json())
        .then(data => populateResults(data));
      
    };
}

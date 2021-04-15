import accessibleAutocomplete from 'accessible-autocomplete'

function suggest (query, populateResults) {
    fetch('/api/autocomplete/guides.json?query='+query)
    .then(response => response.json())
    .then(data => populateResults(data));
  
}


accessibleAutocomplete({
  element: document.querySelector('#my-autocomplete-container'),
  id: 'my-autocomplete',
  showNoOptionsFound: false,
  source: suggest,
  name: 'searchterm'
})
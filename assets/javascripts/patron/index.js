import accessibleAutocomplete from 'accessible-autocomplete'

function suggest (query, populateResults) {
    fetch('/api/autocomplete/guides.json?query='+query)
    .then(response => response.json())
    .then(data => populateResults(data));
  
}

function goToGuide(result) {
  window.location.href = "/subjects/" + result['shortform'];
}

function template(result) {
  return result && result['subject'];
}

accessibleAutocomplete({
  element: document.querySelector('#my-autocomplete-container'),
  id: 'my-autocomplete',
  showNoOptionsFound: false,
  source: suggest,
  name: 'searchterm',
  templates: {
    inputValue: template,
    suggestion: template,
  },
  onConfirm: goToGuide
})
import {createAutocomplete} from 'autocomplete';

function goToGuide(result) {
  window.location.href = "/subjects/" + result['shortform'];
}

function template(result) {
  return result && result['subject'];
}

createAutocomplete(
  '#my-autocomplete-container',
  'my-autocomplete',
  '/api/autocomplete/guides.json?query=',
  'searchterm',
  {
    inputValue: template,
    suggestion: template,
  },
  goToGuide
);

import {createAutocomplete} from 'autocomplete';

function goToDatabase(result) {
  window.location.href = result['url'];
}

function template(result) {
  return result && result['title'];
}

createAutocomplete(
  '#my-autocomplete-container',
  'my-autocomplete',
  '/api/autocomplete/databases.json?query=',
  'searchterm',
  {
    inputValue: template,
    suggestion: template,
  },
  goToDatabase
);

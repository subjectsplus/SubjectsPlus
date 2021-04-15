import accessibleAutocomplete from 'accessible-autocomplete'

const countries = [
  'France',
  'Germany',
  'United Kingdom'
]

accessibleAutocomplete({
  element: document.querySelector('#my-autocomplete-container'),
  id: 'my-autocomplete', // To match it to the existing <label>.
  source: countries,
  name: 'searchterm'
})
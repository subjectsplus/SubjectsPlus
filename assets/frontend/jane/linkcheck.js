import { Collapse } from 'bootstrap';

const checkLinksButton = document.getElementById('check-links-button');
const resultsTable = document.getElementById('results-table');

const paramsCollapse = new Collapse(document.getElementById('collapseParams', {toggle: false}));
const resultsCollapse = new Collapse(document.getElementById('collapseResults', {toggle: false}));

paramsCollapse.show();
resultsCollapse.hide();

checkLinksButton.addEventListener('click', () => {
    paramsCollapse.hide();
    fetch('/control/guides/check_links.json')
    .then(response => response.json())
    .then(data => {
        for (var result of data) {
            var row = resultsTable.insertRow();
            var linkCell = row.insertCell();
            linkCell.innerHTML = result['url'];

            var statusCell = row.insertCell();
            statusCell.innerHTML = result['status'];

            var boxCell = row.insertCell();
            boxCell.innerHTML = "Box";

        }
        resultsCollapse.show();
    })
})
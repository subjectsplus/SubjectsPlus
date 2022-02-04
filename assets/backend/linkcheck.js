const checkLinksButton = document.querySelector('#checkLinksBtn');
const resultsTable = document.querySelector('checkLinksResultsTable');

// TODO: fetch data and display results in table

checkLinksButton.addEventListener('click', () => {
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
    })
})
var exampleEl = document.querySelector('#example');
var popover = new bootstrap.Popover(exampleEl);
console.log("working");

// multiple tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
console.log("working 2");
// TODO: Save mode user preference in database vs. local storage
// Reference: https://css-tricks.com/a-complete-guide-to-dark-mode-on-the-web/#storing-preferences

const adminModeToggleSwitch = document.querySelector('#switchMode');

const currentMode = localStorage.getItem('mode') ? localStorage.getItem('mode') : null;

if (currentMode === 'dark') {
    adminModeToggleSwitch.checked = true;
}

function switchAdminMode(e) {
    if (e.target.checked) {
        document.documentElement.setAttribute('data-mode', 'dark');
        localStorage.setItem('mode', 'dark');
    }
    else {
        document.documentElement.setAttribute('data-mode', 'default');
        localStorage.setItem('mode', 'default');
    }
}

adminModeToggleSwitch.addEventListener('change', switchAdminMode, false);
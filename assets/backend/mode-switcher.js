// TODO: Save user preference for admin theme mode in database vs. local storage
const adminModeToggleSwitch = document.querySelector('#switchMode');

const currentMode = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

if (currentMode) {
    document.documentElement.setAttribute('data-theme', currentMode);

    if (currentMode === 'dark') {
        adminModeToggleSwitch.checked = true;
    }
}

function switchAdminMode(e) {
    if (e.target.checked) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }
    else {
        document.documentElement.setAttribute('data-theme', 'default');
        localStorage.setItem('theme', 'default');
    }
}

adminModeToggleSwitch.addEventListener('change', switchAdminMode, false);
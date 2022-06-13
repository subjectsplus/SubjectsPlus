window.addEventListener('DOMContentLoaded', () => {
    let url = location.href.replace(/\/$/, '');

    if (location.hash) {
        // url has a hash, load the tab indicated by the hash
        const hash = url.split('#');
        const currentTab = document.querySelector('#guide-tabs a[href="#' + hash[1] + '"]');
        
        // intialize and show the tab
        const curTab = new bootstrap.Tab(currentTab);
        curTab.show();

        // if url has a '/#' replaces with singular '#' character
        url = location.href.replace(/\/#/, '#');

        // replace browser url with new one
        history.replaceState(null, null, url);
        
        // scroll to top of window
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 400);
    } else {
        const currentTab = document.querySelector('#tab-0');
        
        // intialize and show the tab
        const curTab = new bootstrap.Tab(currentTab);
        curTab.show();
    }

    // change url based on selected tab
    const selectableTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="pill"]'));

    selectableTabList.forEach((selectableTab) => {
        const selTab = new bootstrap.Tab(selectableTab);
        selectableTab.addEventListener('click', function () {
            let newUrl;
            const hash = selectableTab.getAttribute('href');

            // create new url with hash character and tab_id included
            if (hash == '#tab-0') {
                newUrl = url.split('#')[0];
            } else {
                newUrl = url.split('#')[0] + hash;
            }

            // replace browser url with new one
            history.replaceState(null, null, newUrl);
        });
    });
});
(function() {
    this.DashTab = function(selector) {
        this.globalSetup = function() {
            if (window.isInitializedDashTab) return;

            window.isInitializedDashTab = true;
            this.activateNavLinkByURL();
        };

        this.activateNavLinkByURL = function() {
            let hash = window.location.hash;
            if (!hash) return;

            const navLinks = document.querySelectorAll('.directorist-tab__nav__link');
            navLinks.forEach((link) => {
                const href = link.getAttribute('href');
                const target = link.getAttribute('target');

                if (href === hash || `#${target}` === hash) {
                    const parent = link.closest('.atbdp_tab_nav--has-child');
                    if (parent) {
                        const dropdownMenu = parent.querySelector('.atbd-dashboard-nav');
                        if (dropdownMenu) dropdownMenu.style.display = 'block';
                    }

                    link.click();
                }
            });
        };

        this.navLinksSetup = function(selector) {
            var elements = document.querySelectorAll(selector);
            if (!elements.length) return;

            elements.forEach((el) => {
                const links = el.querySelectorAll('.directorist-tab__nav__link:not(.atbd-dash-nav-dropdown)');
                links.forEach((link) => {
                    link.style.cursor = 'pointer';
                    link.addEventListener('click', (event) => {
                        event.preventDefault();

                        const nav = event.target.closest('.directorist-tab__nav');
                        const main = nav?.nextElementSibling;
                        const items = nav?.querySelectorAll('.directorist-tab__nav__link');
                        const sections = main?.querySelectorAll('.directorist-tab__pane');

                        items?.forEach((item) => item.classList.remove('directorist-tab__nav__active'));
                        sections?.forEach((section) => section.classList.remove('directorist-tab__pane--active'));

                        const targetId = event.target.getAttribute('target');
                        const targetPane = document.getElementById(targetId);
                        if (targetPane) targetPane.classList.add('directorist-tab__pane--active');

                        event.target.classList.add('directorist-tab__nav__active');
                        window.location.hash = `#${targetId}`;
                    });
                });
            });
        };

        if (document.querySelector(selector)) {
            this.navLinksSetup(selector);
            this.globalSetup();
        }
    };
})();

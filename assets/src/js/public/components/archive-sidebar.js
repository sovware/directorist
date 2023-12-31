// Archive Sidebar
window.addEventListener('load', () => {
    const body = document.body;

    // Toggle sidebar and update toggle button's active state
    function toggleSidebar( toggleBtn, archiveSidebar ) {
        archiveSidebar.classList.toggle('listing-with-sidebar__sidebar--open');
        toggleBtn.classList.toggle('directorist-archive-sidebar-toggle--active');
        body.classList.toggle('modal-overlay-enabled');
    }

    // Close sidebar and reset toggle button's active state
    function closeSidebar( closeBtn, archiveSidebar ) {
        archiveSidebar.classList.remove('listing-with-sidebar__sidebar--open');
        closeBtn.classList.remove('directorist-archive-sidebar-toggle--active');
        body.classList.remove('modal-overlay-enabled');
    }

    // Toggle, Close sidebar when toggle/close button is clicked
    body.addEventListener('click', function (e) {
        let targetElement = e.target;
        
        if ( targetElement.classList.contains('directorist-archive-sidebar-toggle') || targetElement.parentElement.classList.contains('directorist-archive-sidebar-toggle') ) {
            let btn = targetElement;
            let sidebar = targetElement.closest('.listing-with-sidebar').querySelector('.listing-with-sidebar__sidebar');

            toggleSidebar(btn, sidebar);

        } else if ( targetElement.classList.contains('directorist-advanced-filter__close') || targetElement.parentElement.classList.contains('directorist-advanced-filter__close') ) {
            let btn = targetElement.closest('.listing-with-sidebar').querySelector('.directorist-archive-sidebar-toggle');
            let sidebar = targetElement.closest('.listing-with-sidebar').querySelector('.listing-with-sidebar__sidebar');
            
            closeSidebar(btn, sidebar);

        } else if ( targetElement.classList.contains('modal-overlay-enabled') ) {
            let archiveSidebar = document.querySelectorAll('.listing-with-sidebar__sidebar');
            archiveSidebar && archiveSidebar.forEach(function (sidebar) {
                if (sidebar.classList.contains('listing-with-sidebar__sidebar--open')) {
                    let btn = sidebar.closest('.listing-with-sidebar').querySelector('.directorist-archive-sidebar-toggle');

                    closeSidebar(btn, sidebar);
                    
                }
            })
        }
    })
    
});
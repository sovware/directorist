// Archive Sidebar
window.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    // Toggle sidebar and update toggle button's active state
    function toggleSidebar( toggleBtn, archiveSidebar ) {
        archiveSidebar.classList.toggle('listing-with-sidebar__sidebar--open');
        toggleBtn.classList.toggle('directorist-archive-sidebar-toggle--active');
    }

    // Close sidebar and reset toggle button's active state
    function closeSidebar( toggleBtn, archiveSidebar ) {
        archiveSidebar.classList.remove('listing-with-sidebar__sidebar--open');
        toggleBtn.classList.remove('directorist-archive-sidebar-toggle--active');
    }

    // Toggle, Close sidebar when toggle/close button is clicked
    body.addEventListener('click', function (e) {
        let targetElement = e.target;

        if ( targetElement.classList.contains('directorist-archive-sidebar-toggle') ) {
            let btn = targetElement;
            let sidebar = targetElement.closest('.listing-with-sidebar').querySelector('.listing-with-sidebar__sidebar');

            toggleSidebar(btn, sidebar);

        } else if ( targetElement.classList.contains('directorist-advanced-filter__close') || targetElement.parentElement.classList.contains('directorist-advanced-filter__close') ) {
            let btn = targetElement.closest('.listing-with-sidebar').querySelector('.directorist-archive-sidebar-toggle');
            let sidebar = targetElement.closest('.listing-with-sidebar').querySelector('.listing-with-sidebar__sidebar');
            
            closeSidebar(btn, sidebar);

        } else {
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
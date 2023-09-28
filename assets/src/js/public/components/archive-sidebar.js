window.addEventListener('DOMContentLoaded', () => {
    /* Archive sidebar toggle */
    const archiveSidebar = document.querySelector('.listing-with-sidebar__sidebar');
    const archiveSidebarToggle = document.querySelector('.directorist-archive-sidebar-toggle');
    const archiveSidebarClose = document.querySelector('.directorist-advanced-filter__close');
    const body = document.body;

    // Toggle sidebar and update toggle button's active state
    function toggleSidebar() {
        archiveSidebar.classList.toggle('listing-with-sidebar__sidebar--open');
        archiveSidebarToggle.classList.toggle('directorist-archive-sidebar-toggle--active');
    }

    // Close sidebar and reset toggle button's active state
    function closeSidebar() {
        if(archiveSidebar) {
            archiveSidebar.classList.remove('listing-with-sidebar__sidebar--open');
        }
        if(archiveSidebarToggle) {
            archiveSidebarToggle.classList.remove('directorist-archive-sidebar-toggle--active');
        }
    }

    // Event delegation for sidebar toggle and close buttons
    function handleSidebarToggleClick(e) {
        e.preventDefault();
        toggleSidebar();
    }

    function handleSidebarCloseClick(e) {
        e.preventDefault();
        closeSidebar();
    }

    // Event delegation for outside click to close sidebar
    function handleOutsideClick(e) {
        if (!e.target.closest('.listing-with-sidebar__sidebar') && e.target !== archiveSidebarToggle) {
            closeSidebar();
        }
    }

    // Attach event listeners
    if(archiveSidebarToggle) {
        archiveSidebarToggle.addEventListener('click', handleSidebarToggleClick);
    }
    if(archiveSidebarClose) {
        archiveSidebarClose.addEventListener('click', handleSidebarCloseClick);
    }
    body.addEventListener('click', handleOutsideClick);
    
});
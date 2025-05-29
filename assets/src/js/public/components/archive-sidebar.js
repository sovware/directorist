// Archive Sidebar
window.addEventListener('load', () => {
	const body = document.body;

	// Toggle sidebar and update toggle button's active state
	function toggleSidebar(toggleBtn, archiveSidebar) {
		archiveSidebar.classList.toggle('listing-with-sidebar__sidebar--open');
		toggleBtn.classList.toggle(
			'directorist-archive-sidebar-toggle--active'
		);
		body.classList.toggle('modal-overlay-enabled');
	}

	// Close sidebar and reset toggle button's active state
	function closeSidebar(toggleBtn, archiveSidebar) {
		archiveSidebar.classList.remove('listing-with-sidebar__sidebar--open');
		toggleBtn.classList.remove(
			'directorist-archive-sidebar-toggle--active'
		);
		body.classList.remove('modal-overlay-enabled');
	}

	// Toggle or close sidebar when toggle/close button is clicked
	body.addEventListener('click', (e) => {
		const targetElement = e.target;
		const toggleBtn = targetElement.closest(
			'.directorist-archive-sidebar-toggle'
		);
		const closeBtn = targetElement.closest(
			'.directorist-advanced-filter__close'
		);

		if (toggleBtn) {
			const sidebar = toggleBtn
				.closest('.listing-with-sidebar')
				.querySelector('.listing-with-sidebar__sidebar');
			toggleSidebar(toggleBtn, sidebar);
		} else if (closeBtn) {
			const sidebar = closeBtn
				.closest('.listing-with-sidebar')
				.querySelector('.listing-with-sidebar__sidebar');
			const toggleBtn = sidebar
				.closest('.listing-with-sidebar')
				.querySelector('.directorist-archive-sidebar-toggle');
			closeSidebar(toggleBtn, sidebar);
		} else if (
			body.classList.contains('modal-overlay-enabled') &&
			!targetElement.closest('.listing-with-sidebar__sidebar')
		) {
			document
				.querySelectorAll('.listing-with-sidebar__sidebar--open')
				.forEach((sidebar) => {
					const toggleBtn = sidebar
						.closest('.listing-with-sidebar')
						.querySelector('.directorist-archive-sidebar-toggle');
					closeSidebar(toggleBtn, sidebar);
				});
		}
	});
});

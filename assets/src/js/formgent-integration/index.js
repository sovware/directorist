import { render, createElement, createRoot } from '@wordpress/element';
import EnquiriesComponent from './components/EnquiriesComponent';
import './index.scss';

// Initialize the React app when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
	const container = document.getElementById(
		'directorist-single-enquiries-js'
	);

	if (!container) {
		return;
	}

	if (createRoot) {
		const root = createRoot(container);
		// Get any localized data from WordPress
		const localizedData = window.directoristFormgentData || {};

		root.render(<EnquiriesComponent data={localizedData} />);
	} else {
		render(<EnquiriesComponent data={localizedData} />, container);
	}
});

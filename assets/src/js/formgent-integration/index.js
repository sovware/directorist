import { render, createElement, createRoot } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import EnquiriesComponent from './components/EnquiriesComponent';
import './notification'; // Import notification system
import './index.scss';

const setupApiFetch = () => {
	const restRoot =
		window?.directorist?.rest_url || window?.wpApiSettings?.root || null;
	const restNonce =
		window?.directorist?.rest_nonce || window?.wpApiSettings?.nonce || null;

	if (restRoot) {
		apiFetch.use(apiFetch.createRootURLMiddleware(restRoot));
	}

	if (restNonce) {
		apiFetch.use(apiFetch.createNonceMiddleware(restNonce));
	}
};

// Initialize the React app when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
	const container = document.getElementById(
		'directorist-single-enquiries-js'
	);

	if (!container) {
		return;
	}

	// Ensure REST requests work for subdirectory.
	setupApiFetch();

	// Get any localized data from WordPress
	const localizedData = window.directoristFormgentData || {};

	if (createRoot) {
		const root = createRoot(container);

		root.render(<EnquiriesComponent data={localizedData} />);
	} else {
		render(<EnquiriesComponent data={localizedData} />, container);
	}
});

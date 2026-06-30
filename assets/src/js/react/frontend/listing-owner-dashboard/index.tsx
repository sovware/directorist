/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';

/**
 * Internal dependencies
 */
import App from './app';

domReady(function () {
	const container = document.querySelector(
		'.directorist-user-dashboard-orders'
	);

	if (!container) {
		return;
	}
	const root = createRoot(container);

	root.render(<App />);
});

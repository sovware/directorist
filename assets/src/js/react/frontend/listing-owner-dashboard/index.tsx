/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

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

	// DataViews uses the default domain for its empty state.
	addFilter(
		'i18n.gettext_default',
		'directorist/dashboard-empty-state',
		(translation, text) => {
			if (text === 'No results' && translation === text) {
				return __('No results', 'directorist');
			}

			return translation;
		}
	);
	const root = createRoot(container);

	root.render(<App />);
});

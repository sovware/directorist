import * as React from 'react';

/**
 * WordPress dependencies
 */
import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import App from './app';

domReady( function () {
	const container = document.querySelector('.directorist-orders-page');

	if (!container) {
		return;
	}
	const root = createRoot(container);

	root.render(<App />);
} );
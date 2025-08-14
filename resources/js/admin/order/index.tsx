import { createRoot } from '@wordpress/element';
import * as React from 'react';
import App from './app';


document.addEventListener('DOMContentLoaded', function () {
	const container = document.querySelector('.directorist-orders-page');

	if (!container) {
		return;
	}
	const root = createRoot(container);

	root.render(<App />);
});

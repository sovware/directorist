import { createRoot } from '@wordpress/element';
import * as React from 'react';

const App = () => {
	return <div>Orders</div>;
};

document.addEventListener('DOMContentLoaded', function () {
	const container = document.querySelector('.directorist-orders-page');

	if (!container) {
		return;
	}
	const root = createRoot(container);

	root.render(<App />);
});

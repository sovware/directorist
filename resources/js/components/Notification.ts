/**
 * WordPress dependencies
 */
import {
	createElement,
	createRoot,
	useCallback,
	useEffect,
} from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import { addAction, doAction, removeAction } from '@wordpress/hooks';

/**
 * External dependencies
 */
import toast, { Toaster } from 'react-hot-toast';

// Define a type for the notification data object for better type safety.
type NotificationData = {
	message?: string;
	type?: 'success' | 'error';
};

const Notification = () => {
	const pushNotification = useCallback((data: NotificationData) => {
		const message = data?.message ?? 'Directorist Notification';
		const type = data?.type ?? 'success';

		if (type === 'success') {
			toast.success(message);
			return;
		}

		toast.error(message);
	}, []);

	// Register the notification handler when the component is mounted
	useEffect(() => {
		console.log('Notification component mounted');
		addAction(
			'directorist-toast',
			'component-directorist-toast',
			pushNotification
		);
		return () => {
			// Clean up the action to prevent memory leaks
			removeAction('directorist-toast', 'component-directorist-toast');
		};
	}, [pushNotification]);

	// `@wordpress/element` and `react-hot-toast` can ship different React type
	// definitions in this project; casting avoids TS incompatibility only.
	return createElement(Toaster as any);
};

domReady(() => {
	const container = document.createElement('div');
	container.setAttribute('id', 'directorist-toast');
	container.setAttribute(
		'style',
		'position: absolute; z-index: 9999999999; font-family: inherit; font-size: 14px;'
	);
	document.body.appendChild(container);

	const root = createRoot(container);
	root.render(createElement(Notification));
});

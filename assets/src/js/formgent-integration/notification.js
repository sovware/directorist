/**
 * WordPress dependencies
 */
import { useCallback, useEffect, createRoot } from '@wordpress/element';
import { addAction, removeAction } from '@wordpress/hooks';
import domReady from '@wordpress/dom-ready';

/**
 * External dependencies
 */
import toast, { Toaster } from 'react-hot-toast';

const Notification = () => {
	const pushNotification = useCallback((data) => {
		const config = Object.assign(
			{
				message: 'Helpgent Notification',
				type: 'success',
			},
			data || {}
		);

		if (config.type === 'success') {
			toast.success(config.message);
		} else {
			toast.error(config.message);
		}
	}, []);

	// Register the notification handler when the component is mounted
	useEffect(() => {
		addAction(
			'helpgent-toast',
			'component-helpgent-toast',
			pushNotification
		);
		return () => {
			// Clean up the action to prevent memory leaks
			removeAction('helpgent-toast', 'component-helpgent-toast');
		};
	}, [pushNotification]);

	return <Toaster />;
};

domReady(() => {
	const container = document.createElement('div');
	container.setAttribute('id', 'helpgent-toast');
	container.setAttribute('style', 'position: absolute; z-index: 9999999999');
	document.body.appendChild(container);

	const root = createRoot(container);
	root.render(<Notification />);
});

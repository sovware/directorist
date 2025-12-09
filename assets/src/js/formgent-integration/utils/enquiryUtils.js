/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { doAction } from '@wordpress/hooks';

/**
 * Utility functions for enquiry operations
 */

/**
 * Extract ID from various item formats
 * @param {Object|Array} item - The item to extract ID from
 * @returns {string|null} - The extracted ID or null if not found
 */
export const extractItemId = (item) => {
	if (Array.isArray(item)) {
		return item[0]?.id || null;
	}

	if (item?.items) {
		return item.items[0]?.id || item.items?.id || null;
	}

	return item?.id || null;
};

/**
 * Extract enquiry data from various item formats
 * @param {Object|Array} item - The item to extract enquiry from
 * @returns {Object|null} - The enquiry object or null if not found
 */
export const extractEnquiryData = (item) => {
	if (Array.isArray(item)) {
		return item[0] || null;
	}
	return item || null;
};

/**
 * Get user email from enquiry data
 * @param {Object} enquiry - The enquiry object
 * @returns {string|null} - The user email or null if not found
 */
export const getUserEmail = (enquiry) => {
	if (!enquiry) return null;
	return enquiry.user?.user_email || enquiry.user_email || null;
};

/**
 * Get listing title from enquiry data
 * @param {Object} enquiry - The enquiry object
 * @returns {string} - The listing title or 'Unknown Listing' if not found
 */
export const getListingTitle = (enquiry) => {
	if (!enquiry) return 'Unknown Listing';
	return enquiry.listing_title || 'Unknown Listing';
};

/**
 * Mark enquiry as read
 * @param {Object|Array} item - The enquiry item
 * @param {Function} onSuccess - Callback function to call on success
 * @returns {Promise} - The API call promise
 */
export const markEnquiryAsRead = async (item, onSuccess) => {
	const responseId = extractItemId(item);

	if (!responseId) {
		console.error('No valid ID found in item:', item);
		return Promise.reject(new Error('No valid ID found'));
	}

	try {
		const data = await apiFetch({
			path: `/wp-json/directorist/formgent/responses/read`,
			method: 'POST',
			data: {
				id: responseId,
			},
		});

		if (onSuccess) {
			onSuccess();
		}

		doAction('helpgent-toast', {
			message: 'Response marked as read',
			type: 'success',
		});

		return data;
	} catch (error) {
		console.error('Error marking as read:', error);
		throw error;
	}
};

/**
 * Delete enquiry
 * @param {Object|Array} item - The enquiry item
 * @param {Function} onSuccess - Callback function to call on success
 * @returns {Promise} - The API call promise
 */
export const deleteEnquiry = async (item, onSuccess) => {
	const responseId = extractItemId(item);

	if (!responseId) {
		console.error('No valid ID found in item:', item);
		return Promise.reject(new Error('No valid ID found'));
	}

	try {
		const data = await apiFetch({
			path: `/wp-json/directorist/formgent/responses`,
			method: 'DELETE',
			data: {
				id: responseId,
			},
		});

		if (onSuccess) {
			onSuccess();
		}

		doAction('helpgent-toast', {
			message: 'Response deleted successfully.',
			type: 'success',
		});

		return data;
	} catch (error) {
		console.error('Error deleting item:', error);
		throw error;
	}
};

/**
 * Send email using native email client
 * @param {Object|Array} item - The enquiry item
 * @returns {void}
 */
export const sendEmailToUser = (item) => {
	const enquiry = extractEnquiryData(item);

	if (!enquiry) {
		console.error('No enquiry data found:', item);
		return;
	}

	const userEmail = getUserEmail(enquiry);
	const listingTitle = getListingTitle(enquiry);

	if (!userEmail) {
		console.error('No user email found for enquiry:', enquiry);
		doAction('helpgent-toast', {
			message: 'No email address found for this enquiry.',
			type: 'error',
		});
		return;
	}

	// Create mailto link with pre-filled fields
	const subject = `Your Enquiry on ${listingTitle}`;
	const mailtoLink = `mailto:${userEmail}?subject=${encodeURIComponent(subject)}`;

	// Open native email client
	window.open(mailtoLink, '_self');
};

/**
 * Fetch single enquiry details
 * @param {string} selectedItem - The selected item ID
 * @returns {Promise} - The API call promise
 */
export const fetchSingleEnquiry = async (selectedItem) => {
	if (!selectedItem) {
		return Promise.reject(new Error('No selected item provided'));
	}

	return apiFetch({
		path: `/wp-json/directorist/formgent/responses/single?id=${selectedItem}`,
		method: 'GET',
	});
};

/**
 * Find matching enquiry from enquiries array based on form_id
 * @param {Object} singleItem - The single item response
 * @param {Array} enquiries - The enquiries array
 * @returns {Object|null} - The matched enquiry or null if not found
 */
export const findMatchingEnquiry = (singleItem, enquiries) => {
	if (
		!singleItem ||
		!singleItem.response ||
		!singleItem.response.form_id ||
		!enquiries
	) {
		return null;
	}

	return (
		enquiries.find(
			(enquiry) => enquiry.form_id === singleItem.response.form_id
		) || null
	);
};

/**
 * Get status badge class based on read status
 * @param {string} isRead - The read status ('1' for read, '0' for unread)
 * @returns {string} - The status badge class
 */
export const getStatusBadgeClass = (isRead) => {
	return isRead === '1' ? 'read' : 'unread';
};

/**
 * Get status badge text based on read status
 * @param {string} isRead - The read status ('1' for read, '0' for unread)
 * @returns {string} - The status badge text
 */
export const getStatusBadgeText = (isRead) => {
	return isRead === '1' ? 'Read' : 'New';
};

/**
 * Fetch enquiry KPIs
 * @returns {Promise} - The API call promise
 */
export const fetchEnquiryKPIs = async () => {
	return apiFetch({
		path: '/wp-json/directorist/formgent/responses/kpis',
		method: 'GET',
	});
};

/**
 * Fetch all enquiries
 * @returns {Promise} - The API call promise
 */
export const fetchAllEnquiries = async () => {
	return apiFetch({
		path: '/wp-json/directorist/formgent/responses',
		method: 'GET',
	});
};

/**
 * Refresh enquiry data (both KPIs and responses)
 * @returns {Promise<Object>} - Object containing responses and kpis
 */
export const refreshEnquiryData = async () => {
	try {
		const [responses, kpis] = await Promise.all([
			fetchAllEnquiries(),
			fetchEnquiryKPIs(),
		]);

		return { responses, kpis };
	} catch (error) {
		console.error('Error refreshing enquiry data:', error);
		throw error;
	}
};

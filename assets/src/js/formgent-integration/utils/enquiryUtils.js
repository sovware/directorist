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
 * @param {boolean} silent - Whether to suppress toast notification
 * @returns {Promise} - The API call promise
 */
export const markEnquiryAsRead = async (item, onSuccess, silent = false) => {
	const responseId = extractItemId(item);

	if (!responseId) {
		console.error('No valid ID found in item:', item);
		return Promise.reject(new Error('No valid ID found'));
	}

	try {
		const data = await apiFetch({
			path: `/directorist/formgent/responses/read`,
			method: 'POST',
			data: {
				id: responseId,
			},
		});

		if (onSuccess) {
			onSuccess();
		}

		if (!silent) {
			doAction('helpgent-toast', {
				message: 'Response marked as read',
				type: 'success',
			});
		}

		return data;
	} catch (error) {
		console.error('Error marking as read:', error);
		throw error;
	}
};

/**
 * Bulk mark multiple enquiries as read with a single notification
 * @param {Array} items - Array of enquiry items to mark as read
 * @param {Function} onSuccess - Callback function to call on success
 * @returns {Promise} - The API call promise
 */
export const bulkMarkEnquiriesAsRead = async (items, onSuccess) => {
	const ids = items.map((item) => extractItemId(item)).filter(Boolean);
	if (ids.length === 0) return;

	try {
		await Promise.all(
			ids.map((id) =>
				apiFetch({
					path: '/directorist/formgent/responses/read',
					method: 'POST',
					data: { id },
				})
			)
		);

		if (onSuccess) onSuccess();

		doAction('helpgent-toast', {
			message: `${ids.length} response(s) marked as read.`,
			type: 'success',
		});
	} catch (error) {
		console.error('Error marking items as read:', error);
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
			path: `/directorist/formgent/responses`,
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
 * Bulk delete multiple enquiries with a single notification
 * @param {Array} items - Array of enquiry items to delete
 * @param {Function} onSuccess - Callback function to call on success
 * @returns {Promise} - The API call promise
 */
export const bulkDeleteEnquiries = async (items, onSuccess) => {
	const ids = items.map((item) => extractItemId(item)).filter(Boolean);
	if (ids.length === 0) return;

	try {
		await Promise.all(
			ids.map((id) =>
				apiFetch({
					path: '/directorist/formgent/responses',
					method: 'DELETE',
					data: { id },
				})
			)
		);

		if (onSuccess) onSuccess();

		doAction('helpgent-toast', {
			message: `${ids.length} response(s) deleted successfully.`,
			type: 'success',
		});
	} catch (error) {
		console.error('Error deleting items:', error);
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
		path: `/directorist/formgent/responses/single?id=${selectedItem}`,
		method: 'GET',
	});
};

/**
 * Find matching enquiry from enquiries array based on enquiry ID
 * @param {Object} singleItem - The single item response
 * @param {Array} enquiries - The enquiries array
 * @returns {Object|null} - The matched enquiry or null if not found
 */
export const findMatchingEnquiry = (singleItem, enquiries) => {
	if (
		!singleItem ||
		!singleItem.response ||
		!singleItem.response.id ||
		!enquiries
	) {
		return null;
	}

	// Match by enquiry ID (response.id) for accurate matching
	return (
		enquiries.find(
			(enquiry) => String(enquiry.id) === String(singleItem.response.id)
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
		path: '/directorist/formgent/responses/kpis',
		method: 'GET',
	});
};

/**
 * Fetch all enquiries
 * @returns {Promise} - The API call promise
 */
export const fetchAllEnquiries = async () => {
	return apiFetch({
		path: '/directorist/formgent/responses',
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

/**
 * Extract title from the first text field in answers array
 * @param {Array} answers - Array of answer objects
 * @returns {string} - The title value or empty string
 */
export const extractTitleFromAnswers = (answers) => {
	if (!Array.isArray(answers) || answers.length === 0) {
		return '';
	}

	const firstTextField = answers.find(
		(answer) => answer.field_type === 'text'
	);

	return firstTextField?.value || firstTextField?.answer || '';
};

/**
 * Extract prefix from the first textarea field in answers array
 * @param {Array} answers - Array of answer objects
 * @returns {string} - The prefix value or empty string
 */
export const extractPrefixFromAnswers = (answers) => {
	if (!Array.isArray(answers) || answers.length === 0) {
		return '';
	}

	const firstTextareaField = answers.find(
		(answer) => answer.field_type === 'textarea'
	);

	return firstTextareaField?.value || firstTextareaField?.answer || '';
};

/**
 * Extract title and prefix from enquiry item
 * Supports both direct answers array and nested response.answers structure
 * @param {Object} item - The enquiry item
 * @returns {Object} - Object with title and prefix
 */
export const extractEnquiryTitleAndPrefix = (item) => {
	if (!item) {
		return { title: '', prefix: '' };
	}

	// Check if answers are directly on the item
	let answers = item.answers;

	// Check if answers are nested in response object
	if (!answers && item.response?.answers) {
		answers = item.response.answers;
	}

	// If still no answers, return empty strings
	if (!Array.isArray(answers) || answers.length === 0) {
		return { title: '', prefix: '' };
	}

	return {
		title: extractTitleFromAnswers(answers),
		prefix: extractPrefixFromAnswers(answers),
	};
};

/**
 * Enrich enquiry items with answers data (for specific items only)
 * Fetches answers for given items and merges them into the item objects
 * Uses caching to avoid refetching the same items
 * @param {Array} items - Array of enquiry items to enrich
 * @param {Map} cache - Cache map to store fetched answers (key: item.id, value: answers array)
 * @returns {Promise<{enrichedItems: Array, cache: Map}>} - Enriched items and updated cache
 */
export const enrichEnquiriesWithAnswers = async (items, cache = new Map()) => {
	if (!Array.isArray(items) || items.length === 0) {
		return { enrichedItems: items, cache };
	}

	// Filter out items that are already cached
	const itemsToFetch = items.filter((item) => {
		return item.id && !cache.has(String(item.id)) && !item.answers;
	});

	if (itemsToFetch.length === 0) {
		// All items are cached, just merge cached data
		const enrichedItems = items.map((item) => {
			const cachedAnswers = cache.get(String(item.id));
			if (cachedAnswers) {
				return { ...item, answers: cachedAnswers };
			}
			return item;
		});
		return { enrichedItems, cache };
	}

	// Fetch answers for items that aren't cached (in parallel)
	const fetchPromises = itemsToFetch.map(async (item) => {
		try {
			const singleEnquiry = await fetchSingleEnquiry(item.id);
			if (singleEnquiry?.response?.answers) {
				// Cache the answers
				cache.set(String(item.id), singleEnquiry.response.answers);
				return {
					item,
					answers: singleEnquiry.response.answers,
				};
			}
			return { item, answers: null };
		} catch (error) {
			console.error(`Error fetching answers for item ${item.id}:`, error);
			return { item, answers: null };
		}
	});

	const fetchedData = await Promise.all(fetchPromises);

	// Create a map for quick lookup
	const fetchedMap = new Map();
	fetchedData.forEach(({ item, answers }) => {
		if (answers) {
			fetchedMap.set(String(item.id), answers);
		}
	});

	// Merge cached and fetched data into items
	const enrichedItems = items.map((item) => {
		const answers =
			fetchedMap.get(String(item.id)) ||
			cache.get(String(item.id)) ||
			item.answers;
		return answers ? { ...item, answers } : item;
	});

	return { enrichedItems, cache };
};

/**
 * Format a date string as relative time or full date.
 * - Under 1 minute  -> "just now"
 * - Under 1 hour    -> "X minutes ago"
 * - Under 24 hours  -> "X hours ago"
 * - Under 3 days    -> "X days ago"
 * - 3 days or older -> full date (e.g. "Feb 9, 2026")
 *
 * @param {string} dateString - A date string (e.g. "2026-02-09 09:53:47")
 * @returns {string} - Formatted relative time or full date
 */
function parseMysqlDateTimeAsUTC(value) {
	// Matches: 2026-02-15 03:00:00 (optionally with milliseconds)
	const match = String(value).match(
		/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})(?::(\d{2}))?(?:\.(\d{1,3}))?$/
	);
	if (!match) return null;

	const year = Number(match[1]);
	const month = Number(match[2]) - 1;
	const day = Number(match[3]);
	const hour = Number(match[4]);
	const minute = Number(match[5]);
	const second = match[6] ? Number(match[6]) : 0;
	const ms = match[7] ? Number(match[7].padEnd(3, '0')) : 0;

	const utcMs = Date.UTC(year, month, day, hour, minute, second, ms);
	const d = new Date(utcMs);
	return Number.isNaN(d.getTime()) ? null : d;
}

function normalizeDateInput(value, { assumeUTC = false } = {}) {
	if (value == null || value === '') return null;

	if (value instanceof Date) {
		return Number.isNaN(value.getTime()) ? null : value;
	}

	// Epoch milliseconds
	if (typeof value === 'number') {
		const d = new Date(value);
		return Number.isNaN(d.getTime()) ? null : d;
	}

	const raw = String(value);

	// MySQL-style datetime strings have no timezone; when requested, treat them as UTC
	// BEFORE letting the browser parse it as local time.
	if (assumeUTC) {
		const utcParsed = parseMysqlDateTimeAsUTC(raw);
		if (utcParsed) return utcParsed;
	}

	// ISO 8601 (with timezone) is safe to hand to the Date parser.
	const isoParsed = new Date(raw);
	if (!Number.isNaN(isoParsed.getTime())) return isoParsed;

	const localParsed = new Date(raw);
	return Number.isNaN(localParsed.getTime()) ? null : localParsed;
}

export const formatRelativeDate = (dateString) => {
	if (!dateString) return '';

	// MySQL-style datetimes have no timezone; treat them as UTC
	const date = normalizeDateInput(dateString, { assumeUTC: true });
	if (!date) return dateString;

	const now = new Date();
	const diffMs = now - date;
	const diffSeconds = Math.floor(diffMs / 1000);
	const diffMinutes = Math.floor(diffSeconds / 60);
	const diffHours = Math.floor(diffMinutes / 60);
	const diffDays = Math.floor(diffHours / 24);

	if (diffSeconds < 60) {
		return 'just now';
	}
	if (diffMinutes < 60) {
		return `${diffMinutes} ${diffMinutes === 1 ? 'minute' : 'minutes'} ago`;
	}
	if (diffHours < 24) {
		return `${diffHours} ${diffHours === 1 ? 'hour' : 'hours'} ago`;
	}
	if (diffDays < 3) {
		return `${diffDays} ${diffDays === 1 ? 'day' : 'days'} ago`;
	}

	// 3 days or older — show full date
	return date.toLocaleDateString('en-US', {
		year: 'numeric',
		month: 'short',
		day: 'numeric',
	});
};

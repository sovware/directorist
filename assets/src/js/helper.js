export const isObject = (value) =>
	value && typeof value === 'object' && !Array.isArray(value);

export function findObjectItem(path, data, defaultValue) {
	if (typeof path !== 'string') {
		return defaultValue;
	}

	if (!isObject(data)) {
		return defaultValue;
	}

	const pathItems = path.split('.');

	let targetItem = data;

	for (const key of pathItems) {
		if (!isObject(targetItem)) {
			return defaultValue;
		}

		if (!targetItem.hasOwnProperty(key)) {
			return defaultValue;
		}

		targetItem = targetItem[key];
	}

	return targetItem;
}

export function directoristRequestHeaders() {
	if (
		window.directorist &&
		window.directorist.request_headers &&
		typeof window.directorist.request_headers === 'object' &&
		!Array.isArray(window.directorist.request_headers)
	) {
		const headers = {};

		for (const key in window.directorist.request_headers) {
			headers[`Directorist-${key}`] =
				window.directorist.request_headers[key];
		}

		return headers;
	}

	return {};
}

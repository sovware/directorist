// Settings field sanitization encodes HTML entities; render decoded text through Vue interpolation.
export const actionText = (value) =>
	String(value || '').replace(
		/&(amp|quot|#0?39|lt|gt);/g,
		(entity) =>
			({
				'&amp;': '&',
				'&quot;': '"',
				'&#039;': "'",
				'&#39;': "'",
				'&lt;': '<',
				'&gt;': '>',
			})[entity]
	);

export const actionFieldKeys = (keys) =>
	Array.isArray(keys)
		? keys.filter((key) => typeof key === 'string' && key.length)
		: [];

export const hasActionFieldValue = (value) =>
	value !== null &&
	value !== undefined &&
	(typeof value === 'string'
		? value.trim().length > 0
		: !Array.isArray(value) || value.length > 0);

// Shared by the settings form and quick-search editor. Missing values retain recovery access.
export function getActionFieldLock(fields, fieldKey) {
	for (const field of Object.values(fields)) {
		const config = field['async-action-config'];
		if (field['async-action'] !== true || !config) continue;
		const required = actionFieldKeys(config.requiredFields);
		if (
			field.asyncActionPending &&
			[...required, ...actionFieldKeys(config.lockFields)].includes(fieldKey)
		) {
			return { locked: true, message: actionText(config.labels?.pending) };
		}
		if (
			field['async-action-state']?.active === true &&
			actionFieldKeys(config.lockFields).includes(fieldKey) &&
			required.every(
				(key) => fields[key] && hasActionFieldValue(fields[key].value)
			)
		) {
			return { locked: true, message: actionText(config.labels?.locked) };
		}
	}
	return { locked: false, message: '' };
}

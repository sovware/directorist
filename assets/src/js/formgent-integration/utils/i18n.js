const getStrings = () => window.directoristFormgentData?.strings || {};

export const t = (key, fallback = '') => {
	const strings = getStrings();
	return strings[key] || fallback;
};

export const tsprintf = (key, fallback = '', ...values) => {
	let index = 0;
	const template = t(key, fallback);

	return template.replace(/%[sd]/g, () => {
		const value = values[index];
		index += 1;
		return typeof value === 'undefined' ? '' : String(value);
	});
};

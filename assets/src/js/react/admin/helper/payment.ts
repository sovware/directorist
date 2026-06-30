export const displayPrice = (price: number, currency: string) => {
	const admin_order = (window as any).directorist_admin_order;

	if (!admin_order) {
		return `${price} ${currency}`;
	}

	const { symbol_position, symbol } = admin_order;

	if (symbol_position === 'before') {
		return `${symbol}${price}`;
	}

	return `${price}${symbol}`;
};

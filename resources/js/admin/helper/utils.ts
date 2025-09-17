
import type { BadgeVariant } from '@wpmvc/components/build-types/gutenberg/badge/type';
// Define interfaces for the user objects
interface WordPressUser {
	ID?: number;
	id?: number;
	first_name?: string;
	last_name?: string;
	name?: string;
	user_email?: string;
	display_name?: string;
	[key: string]: any; // Allow other properties
}

interface NormalizedUser {
	id?: number;
	display_name?: string;
	email?: string;
	[key: string]: any; // Allow other properties
}

interface RootObject {
	user?: WordPressUser;
}

export function getUser(rootObject: RootObject): NormalizedUser | null {
	const user: WordPressUser = { ...rootObject?.user };

	if (!user) {
		return null;
	}

	if (user?.ID) {
		user['id'] = user['ID'];
		delete user['ID'];
	}

	if (
		user?.first_name ||
		user?.first_name === '' ||
		user?.last_name ||
		user?.last_name === ''
	) {
		user['display_name'] =
			`${user?.first_name || ''} ${user?.last_name || ''}`.trim();
		delete user['first_name'];
		delete user['last_name'];
	}

	if (user?.name) {
		user['display_name'] = user.name;
		delete user['name'];
	}

	if (user?.user_email || user?.user_email === '') {
		user['email'] = user.user_email;
		delete user['user_email'];
	}

	return user as NormalizedUser;
}

export function formatDate(
	formatType: string | string[] | undefined,
	date: Date | string | number,
	options?: Intl.DateTimeFormatOptions,
	includeTime: boolean = false
): string {
	const dataObject = date instanceof Date ? date : new Date(date);

	if (Number.isNaN(dataObject.getTime())) {
		return '';
	}

	if (!includeTime) {
		return dataObject.toLocaleDateString(formatType, options);
	}

	// Ensure time fields exist when includeTime=true and none provided
	const hasTimeOptions =
		!!options &&
		('timeStyle' in options ||
			'hour' in options ||
			'minute' in options ||
			'second' in options);

	const mergedOptions: Intl.DateTimeFormatOptions = hasTimeOptions
		? (options as Intl.DateTimeFormatOptions)
		: { ...(options || {}), hour: '2-digit', minute: '2-digit' };

	return dataObject.toLocaleString(formatType, mergedOptions);
}


/**
 * Map subscription status to Badge variant
 */
const STATUS_TO_BADGE_VARIANT: Record<string, BadgeVariant> = {
	paid: 'success',
	pending: 'warning',
	failed: 'error',
	cancelled: 'error',
	refunded: 'default',
	unpaid: 'warning',
	expired: 'error',
};

export function getBadgeVariantByStatus(status: string): BadgeVariant {
	return STATUS_TO_BADGE_VARIANT[status] ?? 'default';
}

export { STATUS_TO_BADGE_VARIANT };

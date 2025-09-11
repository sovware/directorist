import { __ } from '@wordpress/i18n';

export const STATUSES: Record< string, string > = {
	pending: __( 'Pending', 'directorist' ),
	paid: __( 'Paid', 'directorist' ),
	failed: __( 'Failed', 'directorist' ),
	cancelled: __( 'Cancelled', 'directorist' ),
	refunded: __( 'Refunded', 'directorist' ),
	unpaid: __( 'Unpaid', 'directorist' ),
	expired: __( 'Expired', 'directorist' ),
};
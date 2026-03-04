/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { moreVertical } from '@wordpress/icons';
import { DropdownMenu } from '@wordpress/components';

/**
 * External dependencies
 */
import { Column } from '@shamim-ahmed/components/build-types/gutenberg/table/types';
import { Table } from '@shamim-ahmed/dashboard';
import moment from 'moment';

/**
 * Internal dependencies
 */
import Badge from '@/admin/components/badge';
import { STATUSES } from '@/admin/constants/status';
import { displayPrice } from '@/admin/helper/payment';
import { getBadgeVariantByStatus } from '@/admin/helper/utils';
import { OrderTableContainer } from './style';
import { ActionsDropdownWrapper } from './style';

const checkoutPageUrl = directorist_admin_order.checkout_page_url;
const columns: Column[] = [
	{
		id: 'id',
		label: __('Order Id', 'directorist'),
		render: ({ item }) => {
			return (
				`#${item.id}`
			);
		},
	},
	{
		id: 'status',
		label: __('Status', 'directorist'),
		render: ({ item }) => {
			return (
				<Badge variant={ getBadgeVariantByStatus( item.status ) } className='directorist-badge'>
					{ STATUSES[ item.status ] }
				</Badge>
			);
		},
	},
	{
		id: 'total',
		label: __('Total Amount', 'directorist'),
		render: ({ item }) => {
			return (
				<span className="directorist-order-total-amount">
					{displayPrice(item.total_amount, item.currency)}
				</span>
			);
		},
	},
	{
		id: 'order_type',
		label: __('Order Type', 'directorist'),
		render: ({ item }) => {
			return <Badge variant={'info'} className='directorist-badge'>{item?.order_type || __('Unknown', 'directorist')}</Badge>;
		},
	},
	{
		id: 'payment_method',
		label: __('Payment Method', 'directorist'),
		render: ({ item }) => {
			return <span className='directorist-table-text-light'>{item?.payment_method || __('System', 'directorist')}</span>;
		},
	},
	{
		id: 'date',
		label: __('Order Date', 'directorist'),
		render: ({ item }) => {
			return <span className='directorist-table-text-light'>{moment(item.created_at).format('MMM D, YYYY')}</span>;
		},
	},
	{
		id: 'actions',
		label: __( 'Actions', 'directorist' ),
		render: ( { item } ) => {
			const controls: any[] = [];

			if ( item?.status === 'pending' ) {
				controls.push( {
					title: __( 'Pay', 'directorist' ),
					onClick: () => {
						window.location.href = `${checkoutPageUrl}?checkout_type=payment&order_id=${item?.id}`;
					}
				} );
			}

			return (
				<ActionsDropdownWrapper>
					<DropdownMenu
						icon={ moreVertical }
						label={ __( 'Actions', 'directorist' ) }
						controls={ controls }
						placement="right-end"
						toggleProps={ {
							'aria-label': __( 'Package actions', 'directorist' ),
						} }
					/>
				</ActionsDropdownWrapper>
			);
		},
	},
];

export default function App() {
	return (
		<OrderTableContainer>
			<Table
				heading="Orders"
				storeName="directorist/orders"
				path="/directorist/orders"
				columns={columns}
				create={{ status: false }}
				edit={{ status: false }}
				destroy={{ status: false }}
			/>
		</OrderTableContainer>
	);
}

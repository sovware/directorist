/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { Column } from '@wpmvc/components/build-types/gutenberg/table/types';
import { Table } from '@wpmvc/dashboard';
import moment from 'moment';

/**
 * Internal dependencies
 */
import Badge from '@/admin/components/badge';
import { STATUSES } from '@/admin/constants/status';
import { displayPrice } from '@/admin/helper/payment';
import { getBadgeVariantByStatus } from '@/admin/helper/utils';
import { OrderTableContainer } from './style';

const columns: Column[] = [
	{
		id: 'id',
		label: __('Order Id', 'directorist'),
		render: ({ item }) => {
			return (
				<a
					href={`/wp-admin/edit.php?post_type=at_biz_dir&page=directorist-orders#/edit/${item.id}`}
				>
					#{item.id}
				</a>
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
			return <Badge variant={'info'} className='directorist-badge'>{item?.order_type}</Badge>;
		},
	},
	{
		id: 'payment_method',
		label: __('Payment Method', 'directorist'),
	},
	{
		id: 'date',
		label: __('Order Date', 'directorist'),
		render: ({ item }) => {
			return <span className='directorist-table-text-light'>{moment(item.created_at).format('MMM D, YYYY')}</span>;
		},
	},
];

export default function App() {
	return (
		<OrderTableContainer>
			<Table
				heading="Orders"
				storeName="directorist/orders"
				path="/directorist/admin/orders"
				columns={columns}
				create={{ status: false }}
				edit={{ status: false }}
			/>
		</OrderTableContainer>
	);
}

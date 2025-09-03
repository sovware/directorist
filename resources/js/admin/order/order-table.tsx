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
import React from 'react';

/**
 * Internal dependencies
 */
import Badge from '../badge';
import { OrderTableContainer, UserInfoContainer, UserLink } from './styles';

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
				<Badge
					variant={
						item?.status === 'pending'
							? 'warning'
							: item?.status === 'completed'
								? 'success'
								: 'error'
					}
				>
					{item?.status}
				</Badge>
			);
		},
	},
	{
		id: 'total_amount',
		label: __('Total Amount', 'directorist'),
		render: ({ item }) => {
			return (
				<span className="directorist-order-total-amount">
					{item.final_amount} {item.currency}
				</span>
			);
		},
	},
	{
		id: 'order_type',
		label: __('Order Type', 'directorist'),
		render: ({ item }) => {
			return <Badge variant={'success'}>{item?.order_type}</Badge>;
		},
	},
	{
		id: 'payment_method',
		label: __('Payment Method', 'directorist'),
	},
	{
		id: 'user_id',
		label: __('Customer', 'directorist'),
		render: ({ item }) => {
			return (
				<UserInfoContainer>
					<UserLink
						href={`/wp-admin/user-edit.php?user_id=${item.user_id}`}
					>
						{item.user_display_name}
					</UserLink>
					<div>{item.user_email}</div>
				</UserInfoContainer>
			);
		},
	},
	{
		id: 'date',
		label: __('Order Date', 'directorist'),
		render: ({ item }) => {
			return <span>{moment(item.created_at).format('MMM D, YYYY')}</span>;
		},
	},
];

export default function OrderTable() {
	return (
		<OrderTableContainer>
			<Table
				heading="Orders"
				storeName="directorist/orders"
				path="/directorist/admin/orders"
				columns={columns}
				create={{ status: false }}
				edit={{ status: false }}
				// showTable={false}
				// beforeTable={<div>Before Table</div>}
				// cardFooter={<div>Footer</div>}
			/>
		</OrderTableContainer>
	);
}

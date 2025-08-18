/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import React from "react";
import moment from 'moment';
import { Table } from '@wpmvc/dashboard';
import { Column } from '@wpmvc/components/build-types/gutenberg/table/types';

/**
 * Internal dependencies
 */
import { UserInfoContainer, UserLink } from './styles';

const columns: Column[] = [
	{
		id: 'id',
		label: __( 'ID', 'directorist' ),
		render: ( {item} ) => {
			return <a href={`/wp-admin/edit.php?post_type=at_biz_dir&page=directorist-orders#/edit/${item.id}`} >#{item.id}</a>;
		}
	},
	{
		id: 'user',
		label: __( 'User', 'directorist' ),
		render: ( {item} ) => {
			return (
				<UserInfoContainer>
						<UserLink 
							href={`/wp-admin/user-edit.php?user_id=${item.user.ID}`}
						>
							{item.user.display_name}
						</UserLink>
					<div>
						{item.user.user_email}
					</div>
				</UserInfoContainer>
			);
		}
	},
	{
		id: 'order_type',
		label: __( 'Order Type', 'directorist' ),
	},
	{
		id: 'status',
		label: __( 'Status', 'directorist' ),
		render: ( {item} ) => {
			return <span style={{textTransform: 'capitalize'}}>{item.status}</span>;
		}
	},
	
	{
		id: 'total_amount',
		label: __( 'Total Amount', 'directorist' ),
		render: ( {item} ) => {
			return <span>{item.final_amount} {item.currency}</span>;
		}
	},
	
	{
		id: 'payment_method',
		label: __( 'Payment Method', 'directorist' ),
	},
	
	{
		id: 'date',
		label: __( 'Date', 'directorist' ),
		render: ( {item} ) => {
			return <span>{moment(item.created_at).format('MMM D, YYYY')}</span>;
		}
	},
];

export default function OrderTable() {
	return (
		<Table
			heading="Orders"
			path="/directorist/admin/orders"
			columns={ columns }
			create={
				{status: false}
			}
			edit={
				{status: false}
			}
		/>
	);
}
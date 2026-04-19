/**
 * WordPress dependencies
 */
import { Button, DropdownMenu, MenuGroup, MenuItem, Modal } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { moreVertical, seen, trash } from '@wordpress/icons';

/**
 * External dependencies
 */
import { Column } from '@shamim-ahmed/components/build-types/gutenberg/table/types';
import { Table } from '@shamim-ahmed/dashboard';
import { registerCrudStore, useCrudStore } from '@shamim-ahmed/data';
import moment from 'moment';

/**
 * Internal dependencies
 */
import { STATUSES } from '@/admin/constants/status';
import { displayPrice } from '@/admin/helper/payment';
import { getBadgeVariantByStatus, getAdminUrl } from '@/admin/helper/utils';
import Badge from '../../components/badge';
import { DeleteModalContent, GlobalDropdownMenuStyles, OrderTableContainer, UserInfoContainer, UserLink } from './styles';

const baseColumns: Column[] = [
	{
		id: 'id',
		label: __('Order Id', 'directorist'),
		render: ({ item }) => {
			return (
				<>
					<a
						href={`${getAdminUrl()}edit.php?post_type=at_biz_dir&page=directorist-orders#/edit/${item.id}`}
					>
						#{item.id}
					</a>
					{item.legacy_id && (
						<span style={{ marginLeft: '5px', color: '#6b7280', fontSize: '12px', display: 'block' }}>
							{__('Old ID: #', 'directorist')}{item.legacy_id}
						</span>
					)}
				</>
			);
		},
	},
	{
		id: 'status',
		label: __('Status', 'directorist'),
		render: ({ item }) => {
			return (
				<Badge
					variant={getBadgeVariantByStatus(item.status)}
					className="directorist-badge"
				>
					{STATUSES[item.status]}
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
			return (
				<Badge variant={'info'} className="directorist-badge">
					{item?.order_type || __('Unknown', 'directorist')}
				</Badge>
			);
		},
	},
	{
		id: 'payment_method',
		label: __('Payment Method', 'directorist'),
		render: ({ item }) => {
			return (
				<span className="directorist-table-text-light">
					{item?.payment_method || __('System', 'directorist')}
				</span>
			);
		},
	},
	{
		id: 'transaction_id',
		label: __('Transaction ID', 'directorist'),
		render: ({ item }) => {
			return (
				<span className="directorist-table-text-light">
					{item?.transaction_id || '—'}
				</span>
			);
		},
	},
	{
		id: 'user_id',
		label: __('Customer', 'directorist'),
		render: ({ item }) => {
			return (
				<UserInfoContainer>
					<UserLink
						href={`${getAdminUrl()}user-edit.php?user_id=${item.user_id}`}
					>
						{item.user_display_name}
					</UserLink>
					<span className="directorist-table-text-light">
						{item.user_email}
					</span>
				</UserInfoContainer>
			);
		},
	},
	{
		id: 'date',
		label: __('Order Date', 'directorist'),
		render: ({ item }) => {
			return (
				<span className="directorist-table-text-light">
					{moment(item.created_at).format('MMM D, YYYY')}
				</span>
			);
		},
	},
];

export default function Orders() {
	registerCrudStore({ name: 'directorist/orders', path: '/directorist/v1/admin/orders' });
	const { destroy } = useCrudStore({ name: 'directorist/orders' });

	const [ deleteItem, setDeleteItem ] = useState< any >( null );
	const [ isDeleting, setIsDeleting ] = useState( false );

	const handleDelete = async () => {
		setIsDeleting( true );
		try {
			await destroy( deleteItem.id );
			setDeleteItem( null );
		} finally {
			setIsDeleting( false );
		}
	};

	const columns: Column[] = [
		...baseColumns,
		{
			id: 'row_actions',
			label: __( 'Actions', 'directorist' ),
			render: ( { item } ) => (
				<DropdownMenu
					icon={ moreVertical }
					label={ __( 'Actions', 'directorist' ) }
				>
					{ ( { onClose } ) => (
						<>
							<MenuGroup>
								<MenuItem
									icon={ seen }
									onClick={ () => {
										onClose();
										window.location.href = `${ getAdminUrl() }edit.php?post_type=at_biz_dir&page=directorist-orders#/edit/${ item.id }`;
									} }
								>
									{ __( 'View Order', 'directorist' ) }
								</MenuItem>
							</MenuGroup>
							<MenuGroup>
								<MenuItem
									icon={ trash }
									isDestructive
									onClick={ () => {
										onClose();
										setDeleteItem( item );
									} }
								>
									{ __( 'Delete', 'directorist' ) }
								</MenuItem>
							</MenuGroup>
						</>
					) }
				</DropdownMenu>
			),
		},
	];

	return (
		<OrderTableContainer>
			<GlobalDropdownMenuStyles />
			<Table
				heading="Orders"
				storeName="directorist/orders"
				path="/directorist/v1/admin/orders"
				columns={ columns }
				actions={ [] }
				create={ { status: false } }
				edit={ { status: false } }
				destroy={ { status: false } }
			/>

			{ deleteItem && (
				<Modal
					title={ __( 'Delete Order', 'directorist' ) }
					size="small"
					isDismissible={ ! isDeleting }
					onRequestClose={ () => {
						if ( ! isDeleting ) setDeleteItem( null );
					} }
				>
					<DeleteModalContent>
						<p className="delete-modal-message">
							{ __( 'Are you sure you want to permanently delete this order? This action cannot be undone.', 'directorist' ) }
						</p>
						<div className="delete-modal-actions">
							<Button
								variant="secondary"
								onClick={ () => setDeleteItem( null ) }
								disabled={ isDeleting }
							>
								{ __( 'Cancel', 'directorist' ) }
							</Button>
							<Button
								variant="primary"
								isDestructive
								onClick={ handleDelete }
								isBusy={ isDeleting }
								disabled={ isDeleting }
							>
								{ __( 'Delete', 'directorist' ) }
							</Button>
						</div>
					</DeleteModalContent>
				</Modal>
			) }
		</OrderTableContainer>
	);
}

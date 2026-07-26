/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { Button, DropdownMenu, Modal } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { moreVertical } from '@wordpress/icons';

/**
 * External dependencies
 */
import { Column } from '@shamim-ahmed/components/build-types/gutenberg/table/types';
import { registerCrudStore, useCrudStore } from '@shamim-ahmed/data';
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
const orderStoreName = 'directorist/orders';
const orderStorePath = '/directorist/v2/orders';

const baseColumns: Column[] = [
	{
		id: 'id',
		label: __('Order Id', 'directorist'),
		render: ({ item }) => {
			return (
				<>
					<span>#{item.id}</span>
					{item.legacy_id && (
						<span style={{  marginLeft: '5px', color: '#6b7280', fontSize: '12px', display: 'block' }}>
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
		id: 'date',
		label: __('Order Date', 'directorist'),
		render: ({ item }) => {
			return (
				<span className="directorist-table-text-light">
					{moment(item.created_at).format('MMMM D, YYYY')}
				</span>
			);
		},
	},
];

export default function App() {
	registerCrudStore({ name: orderStoreName, path: orderStorePath });
	const { updateItem } = useCrudStore({ name: orderStoreName });
	const [cancelItem, setCancelItem] = useState<any>(null);
	const [isCancelling, setIsCancelling] = useState(false);
	const [cancelError, setCancelError] = useState('');

	const handleCancelOrder = async () => {
		if (!cancelItem?.id) return;

		setIsCancelling(true);
		setCancelError('');

		try {
			await apiFetch({
				path: `${orderStorePath}/${cancelItem.id}/cancel`,
				method: 'POST',
			});
			updateItem(cancelItem.id, { status: 'cancelled' });
			setCancelItem(null);
		} catch (error) {
			setCancelError(
				error?.message ||
					__('Failed to cancel order. Please try again.', 'directorist')
			);
		} finally {
			setIsCancelling(false);
		}
	};

	const columns: Column[] = [
		...baseColumns,
		{
			id: 'actions',
			label: __('Actions', 'directorist'),
			render: ({ item }) => {
				if (item?.status !== 'pending') {
					return <span className="directorist-table-text-light">—</span>;
				}

				const controls: any[] = [
					{
						title: __('Pay', 'directorist'),
						onClick: () => {
							window.location.href = `${checkoutPageUrl}?checkout_type=payment&order_id=${item?.id}`;
						},
					},
					{
						title: __('Cancel', 'directorist'),
						onClick: () => {
							setCancelError('');
							setCancelItem(item);
						},
					},
				];

				return (
					<ActionsDropdownWrapper>
						<DropdownMenu
							icon={moreVertical}
							label={__('Actions', 'directorist')}
							controls={controls}
							placement="right-end"
							toggleProps={{
								'aria-label': __('Order actions', 'directorist'),
							}}
						/>
					</ActionsDropdownWrapper>
				);
			},
		},
	];

	return (
		<OrderTableContainer>
			<Table
				heading="Orders"
				storeName={orderStoreName}
				path={orderStorePath}
				columns={columns}
				create={{ status: false }}
				edit={{ status: false }}
				destroy={{ status: false }}
			/>

			{cancelItem && (
				<Modal
					title={__('Cancel Order', 'directorist')}
					size="small"
					isDismissible={!isCancelling}
					onRequestClose={() => {
						if (!isCancelling) setCancelItem(null);
					}}
				>
					<p>
						{__(
							'Are you sure you want to cancel this pending order?',
							'directorist'
						)}
					</p>
					{cancelError && (
						<p className="directorist-error__msg">{cancelError}</p>
					)}
					<div className="directorist-payment-action directorist-flex directorist-justify-content-between">
						<Button
							variant="secondary"
							onClick={() => setCancelItem(null)}
							disabled={isCancelling}
						>
							{__('Keep Order', 'directorist')}
						</Button>
						<Button
							variant="primary"
							isDestructive
							isBusy={isCancelling}
							disabled={isCancelling}
							onClick={handleCancelOrder}
						>
							{__('Cancel Order', 'directorist')}
						</Button>
					</div>
				</Modal>
			)}
		</OrderTableContainer>
	);
}

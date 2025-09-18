/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { Table } from '@wpmvc/dashboard';
import { registerCrudStore, useCrudStoreData } from '@wpmvc/data';
import { FieldsType } from '@wpmvc/fields/build-types/types/field';

/**
 * Internal dependencies
 */
import Badge from '@/admin/components/badge';
import { getBadgeVariantByStatus } from '@/admin/helper/utils';
import AngleDownIcon from '@/admin/icons/AngleDownIcon';
import AngleUpIcon from '@/admin/icons/AngleUpIcon';
import RefundSummary from './refund-summary';
import { RefundTable, RefundTableToggle } from './styles';
import { displayPrice } from '@/admin/helper/payment';

const columns = [
	{ id: 'id', label: __('Refund ID', 'directorist') },
	{ 
		id: 'amount',
		label: __('Amount', 'directorist'),
		render: ({ item }: { item: any }) => {
			return (
				<span className="directorist-refund-amount">
					{displayPrice(item?.amount, item?.currency)}
				</span>
			);
		},	
	},
	{ id: 'reason', label: __('Reason', 'directorist') },
	{
		id: 'status',
		label: __('Status', 'directorist'),
		render: ({ item }: { item: any }) => {
			return (
				<Badge
					className='directorist-badge'
					variant={ getBadgeVariantByStatus(item?.status) }
				>
					{item?.status}
				</Badge>
			);
		},
	},
	{ id: 'created_at', label: __('Date', 'directorist') },
];

export default function Refund({ order }: { order: any }) {
	const [showRefundTable, setShowRefundTable] = useState(false);

	registerCrudStore({
		name: 'directorist/order-refund',
		path: `/directorist/admin/orders/${order?.id}/refunds`,
	});

	const { data: refundsData } = useCrudStoreData({
		name: 'directorist/order-refund',
		selector: 'get',
	});

	const availableRefundAmount =
		parseFloat(order?.total_amount ?? '0') -
		parseFloat(refundsData?.total_refunded ?? '0');

	const refundFields: FieldsType = {
		amount: {
			type: 'number',
			label: __('Refund amount', 'directorist'),
		},
		reason: {
			type: 'text',
			label: __('Reason for refund', 'directorist'),
		},
		status: {
			type: 'select',
			label: __('Status', 'directorist'),
			options: [
				{ label: __('Pending', 'directorist'), value: 'pending' },
				{ label: __('Paid', 'directorist'), value: 'paid' },
				{ label: __('Failed', 'directorist'), value: 'failed' },
				{ label: __('Cancelled', 'directorist'), value: 'cancelled' },
				{ label: __('Refunded', 'directorist'), value: 'refunded' },
				{ label: __('Unpaid', 'directorist'), value: 'unpaid' },
				{ label: __('Expired', 'directorist'), value: 'expired' },
			],
			isMulti: false,
			menuPosition: 'fixed',
		},
	};

	return (
		<RefundTable>
			<Table
				heading={__('Refund Management', 'directorist')}
				storeName="directorist/order-refund"
				path={`/directorist/admin/orders/${order?.id}/refunds`}
				columns={columns}
				showTable={showRefundTable}
				create={{
					title: __('Add Refund', 'directorist'),
					buttonLabel: __('Add Refund', 'directorist'),
					fields: refundFields,
					isDisabled: availableRefundAmount <= 0 ? true : false,
				}}
				edit={{
					title: __('Edit Refund', 'directorist'),
					buttonLabel: __('Edit Refund', 'directorist'),
					fields: refundFields,
				}}
				beforeTable={
					<RefundSummary
						refundsData={refundsData}
						availableRefundAmount={availableRefundAmount}
					/>
				}
				cardFooter={
					<RefundTableToggle
						onClick={() => setShowRefundTable(!showRefundTable)}
					>
						{showRefundTable ? (
							<>
								<span>
									{__('Hide Refund History', 'directorist')}
								</span>
								<span className="directorist-refund-table-toggle-up-icon">
									{' '}
									<AngleUpIcon />
								</span>
							</>
						) : (
							<>
								<span>
									{' '}
									{__('Refund History', 'directorist')}
								</span>
								<span className="directorist-refund-table-toggle-down-icon">
									{' '}
									<AngleDownIcon />
								</span>
							</>
						)}
					</RefundTableToggle>
				}
			/>
		</RefundTable>
	);
}

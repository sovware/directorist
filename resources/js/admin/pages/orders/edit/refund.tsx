import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Table } from '@wpmvc/dashboard';
import { registerCrudStore, useCrudStoreData } from '@wpmvc/data';
import { FieldsType } from '@wpmvc/fields/build-types/types/field';
import React from 'react';
import styled from 'styled-components';
import Badge from '@/admin/components/badge';
import AngleDownIcon from '@/admin/icons/AngleDownIcon';
import AngleUpIcon from '@/admin/icons/AngleUpIcon';
import RefundSummary from './refund-summary';

// Register the store outside the component to ensure it's available before any component mounts
const RefundTable = styled.div`
	> div {
		padding: 0;
	}
	margin-bottom: 24px;
	.components-card__body{
		padding: 0;
	}
	.dataviews-wrapper{
		border-top: 1px solid var(--color-light);
	}
`;
const RefundTableToggle = styled.span`
	display: flex;
	align-items: center;
	cursor: pointer;
	color: var(--color-primary-500);
	.directorist-refund-table-toggle-down-icon{
		position: relative;
		top: 4px;
	}
	svg{
		margin-left: 6px;
	}
`;

const columns = [
	{ id: 'id', label: 'Refund ID' },
	{ id: 'amount', label: 'Amount' },
	{ id: 'created_at', label: 'Date' },
	{ id: 'reason', label: 'Reason' },
	{
		id: 'status',
		label: 'Status',
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
];

export default function Refund({ order }) {
	const [showRefundTable, setShowRefundTable] = useState(false);
	

	registerCrudStore({
		name: 'directorist/order-refund',
		path: `/directorist/admin/orders/${order?.id}/refunds`,
	});

	const { data: refundsData } = useCrudStoreData({
		name: 'directorist/order-refund',
		selector: 'get',
	});
	const availableRefundAmount = parseFloat(order?.final_amount ?? "0") - parseFloat(refundsData?.total_refunded ?? "0");

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
				heading="Refund Management"
				storeName="directorist/order-refund"
				path={`/directorist/admin/orders/${order?.id}/refunds`}
				columns={columns}
				showTable={showRefundTable}
				create= {{
					buttonLabel: __("Add Refund", "directorist"),
					fields: refundFields,
					isDisabled: availableRefundAmount <=0 ? true : false
				}}
				beforeTable={<RefundSummary refundsData={refundsData} availableRefundAmount={availableRefundAmount} />}
				cardFooter={
					<RefundTableToggle
						onClick={() => setShowRefundTable(!showRefundTable)}
					>
						{showRefundTable ? (
							<>
								<span>{__("Hide Refund History", "directorist")}</span>
								<span className="directorist-refund-table-toggle-up-icon"> <AngleUpIcon /></span>
							</>
						) : (
							<>
								<span> {__("Refund History", "directorist")}</span>
								<span className="directorist-refund-table-toggle-down-icon"> <AngleDownIcon /></span>
							</>
						)}
					</RefundTableToggle>
				}
			/>
		</RefundTable>
	);
}

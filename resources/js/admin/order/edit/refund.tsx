import { useState } from '@wordpress/element';
import { Table } from '@wpmvc/dashboard';
import React from 'react';
import styled from 'styled-components';
import Badge from '../../badge';
import AngleDownIcon from '../../icons/angleDownIcon';
import AngleUpIcon from '../../icons/AngleUpIcon';

// Register the store outside the component to ensure it's available before any component mounts

const RefundHistoryContainer = styled.div`
	padding: 16px 32px;
	border-top: 1px solid rgba(0, 0, 0, 0.1);
`;
const RefundTable = styled.div`
	> div {
		padding: 0;
	}
`;
const RefundTableToggle = styled.span``;

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

const addRefundInitialValues = {
	amount: 0,
	reason: '',
	status: 'pending',
};

export default function Refund({ order }) {
	const [showRefundTable, setShowRefundTable] = useState(false);
	const [searchTerm, setSearchTerm] = useState('');
	const [currentPage, setCurrentPage] = useState(1);
	const [perPage, setPerPage] = useState(10);

	function handleRefresh(params) {
		setSearchTerm(params.search || '');
		setCurrentPage(params.page || 1);
		setPerPage(params.perPage || 10);
	}

	return (
		<RefundHistoryContainer>
			{showRefundTable && (
				<RefundTable>
					<Table
						heading="Refunds"
						storeName="directorist/order-refund"
						path={`/directorist/admin/orders/${order?.id}/refunds`}
						columns={columns}
						create={{ status: false }}
					/>
				</RefundTable>
			)}

			<RefundTableToggle
				onClick={() => setShowRefundTable(!showRefundTable)}
			>
				{showRefundTable ? (
					<>
						<span>Hide Refund History</span>
						<AngleDownIcon />
					</>
				) : (
					<>
						<span>Refund History</span>
						<AngleUpIcon />
					</>
				)}
			</RefundTableToggle>
		</RefundHistoryContainer>
	);
}

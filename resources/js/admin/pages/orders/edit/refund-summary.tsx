import { __ } from '@wordpress/i18n';
import React from 'react';
import styled from 'styled-components';
import ChartCircleIcon from '@/admin/icons/ChartCircleIcon';
import CheckCircleIcon from '@/admin/icons/CheckCircleIcon';

const RefundSummaryContainer = styled.div`
	display: flex;
	align-items: center;
	padding: 24px 32px;
	.directorist-refund-summary-item {
		position: relative;
		display: flex;
		align-items: center;
		gap: 12px;
		&:not(:last-child) {
			padding-right: 30px;
			margin-right: 30px;
			&::before {
				content: '';
				position: absolute;
				right: 0;
				top: 50%;
				transform: translateY(-50%);
				width: 1px;
				height: 30px;
				background-color: var(--color-gray-300);
			}
		}
	}
	.directorist-refund-summary-item__content {
		display: flex;
		flex-direction: column;
		gap: 5px;
	}
	.directorist-refund-summary-item__label {
		font-size: 11px;
		font-weight: 500;
		text-transform: uppercase;
		color: var(--color-gray-600);
	}
	.directorist-refund-summary-item__value {
		font-size: 16px;
		font-weight: 500;
		color: var(--color-gray-900);
	}
`;

export default function RefundSummary({ refundsData, availableRefundAmount }) {
	return (
		<RefundSummaryContainer>
			<div className="directorist-refund-summary-item">
				<span className="directorist-refund-summary-item__icon">
					<CheckCircleIcon />
				</span>
				<span className="directorist-refund-summary-item__content">
					<span className="directorist-refund-summary-item__label">
						{__('Amount re-funded', 'directorist')}
					</span>
					<span className="directorist-refund-summary-item__value">
						${refundsData?.total_refunded ?? 0}
					</span>
				</span>
			</div>
			<div className="directorist-refund-summary-item">
				<span className="directorist-refund-summary-item__icon">
					<ChartCircleIcon />
				</span>
				<span className="directorist-refund-summary-item__content">
					<span className="directorist-refund-summary-item__label">
						{__('Available to re-fund', 'directorist')}
					</span>
					<span className="directorist-refund-summary-item__value">
						${availableRefundAmount}
					</span>
				</span>
			</div>
		</RefundSummaryContainer>
	);
}

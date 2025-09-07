import styled from "styled-components";

export const RefundSummaryContainer = styled.div`
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


export const RefundTable = styled.div`
	> div {
		padding: 0;
	}
	margin-bottom: 24px;
	.components-card__body {
		padding: 0;
	}
	.dataviews-wrapper {
		border-top: 1px solid var(--color-light);
	}
`;

export const RefundTableToggle = styled.span`
	display: flex;
	align-items: center;
	cursor: pointer;
	color: var(--color-primary-500);
	.directorist-refund-table-toggle-down-icon {
		position: relative;
		top: 4px;
	}
	svg {
		margin-left: 6px;
	}
`;
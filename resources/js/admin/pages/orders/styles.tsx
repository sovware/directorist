import styled from 'styled-components';

export const UserInfoContainer = styled.div`
	display: flex;
	flex-direction: column;
	gap: 4px;
	padding: 8px 0;
`;

export const UserLink = styled.a`
	color: #3a6fac;
	text-decoration: none;

	&:hover {
		text-decoration: underline;
	}
`;

export const OrderTableContainer = styled.div`
	padding: 24px;
	.dataviews-view-table {
		color: var(--color-gray-500);
		thead th {
			background-color: #f9f9f9;
			border-bottom: 1px solid #dddddd;
		}
	}
	.dataviews__view-actions,
	.dataviews-filters__container {
		padding: 16px 42px;
	}
	.dataviews__view-actions {
		.components-input-control__container {
			background-color: #f0f0f0;
		}
	}

	.directorist-order-total-amount {
		color: var(--color-gray-900);
	}
	.dataviews-view-table tr .dataviews-item-actions .components-button:not(.dataviews-all-actions-button){
		opacity: 1;
	}
`;

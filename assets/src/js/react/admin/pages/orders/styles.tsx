import styled, { createGlobalStyle } from 'styled-components';

export const GlobalDropdownMenuStyles = createGlobalStyle`
	.components-dropdown-menu__popover .components-menu-item__item {
		min-width: unset;
		margin-right: 0;
		order: 2;
	}
	.components-dropdown-menu__popover .components-menu-item__button.components-button {
		gap: 8px;
		justify-content: flex-start;
	}
	.components-dropdown-menu__popover .components-menu-item__button.components-button > svg {
		order: 1;
		flex-shrink: 0;
		margin: 0 !important;
	}
`;

export const DeleteModalContent = styled.div`
	display: flex;
	flex-direction: column;
	align-items: center;

	.delete-modal-message {
		font-size: 14px;
		color: var( --wpmvc-gray-500, #6b7280 );
		line-height: 1.6;
		margin: 0 0 24px;
	}

	.delete-modal-actions {
		display: flex;
		gap: 12px;
		width: 100%;

		.components-button {
			flex: 1;
			justify-content: center;
			height: 40px;
		}
	}
`;

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
		color: var(--wpmvc-gray-500);

		th,
		td {
			vertical-align: middle;
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
		color: var(--wpmvc-gray-900);
	}
`;


import styled from "styled-components";

export const UserInfoContainer = styled.div`
	display: flex;
	flex-direction: column;
	gap: 4px;
	padding: 8px 0;
`;

export const UserLink = styled.a`
	color: #3A6FAC;
	text-decoration: none;

	&:hover {
		text-decoration: underline;
	}
`;

export const OrderTableContainer = styled.div`
	.dataviews-view-table {
		color: var(--color-gray-500);
		thead th{
			background-color: #F9F9F9;
			border-bottom: 1px solid #DDDDDD;
		}
	}
	.dataviews__view-actions, 
	.dataviews-filters__container{
		padding: 16px 42px;
	}
	.dataviews__view-actions{
		.components-input-control__container{
			background-color: #F0F0F0;
		}
	}
	
	.directorist-order-total-amount{
		color: var(--color-gray-900);
	}
`;
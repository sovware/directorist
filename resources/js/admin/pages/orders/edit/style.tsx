import styled from 'styled-components';

const InfoHead = styled.div`
	.directorist-order-details-label {
		display: flex;
		align-items: center;
		margin: 0 0 6px;
	}
	.directorist-order-id {
		font-size: 24px;
		font-weight: 400;
		color: var(--color-gray-900);
		margin-right: 10px;
	}
	.directorist-order-details-meta {
		font-size: 12px;
		font-weight: 400;
		color: var(--color-gray-600);
	}
`;

const InfoList = styled.ul`
	&.directorist-has-border {
		li {
			border-top: 1px solid var(--color-gray-200);
			padding: 14px 0 7px;
		}
	}
	li {
		display: flex;
		justify-content: space-between;
		margin: 0;
		padding-bottom: 14px;

		&.directorist-list-highlight {
			span {
				font-size: 14px;
				font-weight: 500;
				color: var(--color-gray-900);
			}
		}
		&:last-child {
			border-bottom: none;
			padding-bottom: 0;
		}
		span {
			font-size: 12px;
			color: var(--color-gray-600);
			font-weight: 500;
		}
		.directorist-list-coupon {
			.directorist-badge {
				margin-right: 8px;
			}
		}
	}
`;

const LogList = styled.ul``;

const LogItem = styled.li`
	span {
		font-size: 12px;
		font-weight: 400;
		margin: 6px 0 0;
		display: block;
		color: var(--color-gray-500);
	}
	p {
		font-size: 14px;
		font-weight: 400;
		margin: 2px 0 0;
		color: var(--color-gray-900);
	}
`;

const InfoBox = styled.ul`
	.directorist-infobox-item {
		display: flex;
		flex-direction: column;
		gap: 5px;
		&:not(:last-child) {
			margin-bottom: 14px;
		}
		.directorist-infobox-item-label {
			font-size: 14px;
			font-weight: 500;
			color: var(--color-gray-900);
		}
		.directorist-infobox-item-text {
			font-size: 12px;
			font-weight: 400;
			color: var(--color-gray-500);
		}
	}
`;

const SubscriptionAction = styled.div`
	padding: 16px 32px;
	border-top: 1px solid rgba(0, 0, 0, 0.1);
	a {
		text-decoration: underline;
		color: var(--color-primary-500);
		font-size: 12px;
		font-weight: 600;
	}
`;

export { InfoBox, InfoHead, InfoList, LogItem, LogList, SubscriptionAction };

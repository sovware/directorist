import styled from 'styled-components';

const EnquiriesComponentStyle = styled.div`
	.directorist-enquiries-header {
		margin-bottom: 32px;
	}
	.directorist-enquiries-title {
		font-size: 30px;
		font-weight: 700;
		margin: 0;
	}
	.directorist-enquiries-description {
		font-size: 14px;
		margin: 8px 0 0 0;
	}
	.directorist-enquires-stats {
		display: flex;
		align-items: center;
		gap: 20px;
	}
	.directorist-enquires-stats-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-radius: 12px;
		width: 25%;
		background-color: #fff;
		padding: 24px;
		border: 1px solid #e5e7eb;
		transition: 0.3s ease;
		&:hover {
			box-shadow:
				0 0 #0000,
				0 0 #0000,
				0 4px 6px -1px rgb(0 0 0 / 0.1),
				0 2px 4px -2px rgb(0 0 0 / 0.1);
		}
	}
	.directorist-enquires-stats-left {
		h2 {
			font-size: 30px;
			font-weight: 700;
			margin: 0;
		}
		p {
			font-size: 14px;
			margin: 4px 0 0 0;
		}
	}
	.directorist-enquires-stats-right {
		span {
			width: 48px;
			height: 48px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 8px;
			background-color: #dbeafe;
			svg {
				fill: var(--directorist-color-info);
				width: 24px;
				height: 24px;
			}
		}
	}
	.directorist-enquires-stats-item--total {
		.directorist-enquires-stats-right {
			span {
				background-color: #dbeafe;
			}
			svg {
				fill: var(--directorist-color-info);
			}
		}
	}
	.directorist-enquires-stats-item--new {
		.directorist-enquires-stats-right {
			span {
				background-color: #ffedd5;
			}
			svg {
				fill: var(--directorist-color-warning);
			}
		}
	}
	.directorist-enquires-stats-item--this-week {
		.directorist-enquires-stats-right {
			span {
				background-color: #dbeafe;
			}
			svg {
				fill: var(--directorist-color-info);
			}
		}
	}
	.directorist-enquires-stats-item--resolved {
		.directorist-enquires-stats-right {
			span {
				background-color: #dcfce7;
			}
			svg {
				fill: var(--directorist-color-success);
			}
		}
	}
	.directorist-enquiries-table {
		background: #fff;
		border-radius: 12px;
		border: 1px solid #e5e7eb;
		margin-top: 30px;
		.dataviews-view-table {
			tbody {
				td {
					vertical-align: middle;
				}
			}
		}
		.dataviews__view-actions {
			border-bottom: 1px solid #e5e7eb;
		}
		.directorist-table-enquiry,
		.directorist-table-enquiry-listing,
		.directorist-table-enquiry-sender {
			h2 {
				font-size: 14px;
				font-weight: 500;
				color: #1e1e1e;
				margin: 0;
			}
		}
		.dataviews-view-table__actions-column {
			display: flex;
			align-items: center;
			gap: 5px;
		}
	}
	.directorist-table-enquiry-sender {
		display: flex;
		align-items: center;
		gap: 12px;
		.directorist-table-enquiry-sender-avatar {
			display: flex;
			align-items: center;
			img {
				width: 32px;
				height: 32px;
				border-radius: 50%;
				object-fit: cover;
			}
		}
		.directorist-table-enquiry-sender-info {
			h2 {
				font-size: 14px;
				font-weight: 600;
				color: #1e1e1e;
				margin: 0;
			}
			p {
				font-size: 12px;
				margin: 0 0 0 0;
			}
		}
	}
	.directorist-table-enquiry {
		p {
			margin: 8px 0;
		}
	}
	.directorist-table-enquiry-listing {
		span {
			display: block;
			margin-top: 8px;
		}
	}
	.directorist-table-enquiry-action {
		display: flex;
		gap: 15px;
		a {
			text-decoration: none;
			font-weight: 500;
			color: var(--directorist-color-info);
		}
	}
	.dataviews-view-table__actions-column {
		padding: 30px 0;
	}
`;

const EnquiryDetailsModalStyle = styled.div`
	margin-top: 20px;
	.directorist-enquiry-sender {
		display: flex;
		gap: 16px;
		align-items: flex-start;
		margin-bottom: 24px;
	}
	.directorist-enquiry-sender-avatar img {
		width: 48px;
		height: 48px;
		border-radius: 50%;
		object-fit: cover;
	}
	.directorist-enquiry-sender-info {
		h2 {
			margin: 0 0 8px 0;
			display: flex;
			align-items: center;
			gap: 12px;
			font-size: 16px;
			font-weight: 600;
		}
		p {
			margin: 0 0 8px 0;
			font-size: 14px;
			color: var(--directorist-color-body);
		}
		span {
			display: flex;
			font-size: 12px;
		}
	}
	.directorist-enquiry-listing {
		margin-bottom: 24px;
		h3 {
			margin: 0 0 4px 0;
			font-size: 16px;
			font-weight: 600;
		}
		a {
			text-decoration: none;
			color: var(--directorist-color-info);
			font-size: 14px;
			font-weight: 500;
		}
	}
	.directorist-answers-section {
		margin-bottom: 110px;
	}
	.directorist-enquiry-answer-item {
		margin-bottom: 24px;
	}
	.directorist-enquiry-answer-title {
		margin: 0 0 10px 0;
		font-size: 16px;
		font-weight: 600;
		color: var(--directorist-color-dark);
	}
	.directorist-enquiry-answer-value {
		margin: 0;
		font-size: 14px;
		font-weight: 500;
		color: var(--directorist-color-deep-gray);
	}
	.directorist-enquiry-answer-child {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 5px;
	}
	.directorist-enquiry-answer-title-child {
		margin: 0;
		font-size: 14px;
		font-weight: 600;
		color: var(--directorist-color-dark);
	}
	.directorist-enquiry-answer-value-child {
		margin: 0;
		font-size: 14px;
		font-weight: 400;
	}
	.directorist-enquiry-answer-repeater {
		table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
			border-radius: 8px;
			overflow: hidden;
			border: 1px solid #eee;
		}

		thead {
			background: linear-gradient(to bottom, #f9f9f9, #f1f3f5);
		}

		th {
			text-align: left;
			padding: 12px 16px;
			font-weight: 600;
			font-size: 14px;
			color: #333;
		}

		td {
			padding: 12px 16px;
			font-size: 14px;
			color: #000;
			border-top: 1px solid #eee;
		}

		/* Hover effect */
		tr:hover td {
			background-color: #fafafa;
		}
	}
	//stick footer to bottom
	.directorist-enquiry-modal-footer {
		border-top: 1px solid #e5e7eb;
		display: flex;
		gap: 12px;
		position: fixed;
		bottom: 0;
		background-color: #fff;
		width: 100%;
		padding: 20px 30px;
		box-sizing: border-box;
		left: 0;
	}
	.directorist-enquiry-modal-btn {
		display: flex;
		align-items: center;
		gap: 8px;
		padding: 0 16px;
		min-height: 42px;
		border-radius: 8px;
		font-size: 14px;
		font-weight: 500;
		box-shadow: none;
		border: 1px solid #e5e7eb;
		background: none;
		cursor: pointer;
		transition: 0.3s ease;
	}
	.directorist-enquiry-modal-btn-delete {
		margin-left: auto;
		&:hover {
			border-color: var(--directorist-color-danger);
			color: var(--directorist-color-danger);
		}
	}
	.directorist-enquiry-modal-btn-resolved {
		&:hover {
			border-color: var(--directorist-color-success);
			color: var(--directorist-color-success);
		}
	}
	.directorist-enquiry-modal-btn-reply {
		&:hover {
			border-color: var(--directorist-color-info);
			color: var(--directorist-color-info);
		}
	}
`;

export { EnquiriesComponentStyle, EnquiryDetailsModalStyle };

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
		flex-wrap: wrap;
		align-items: center;
		gap: 20px;
	}
	.directorist-enquires-stats-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex: 1;
		border-radius: 12px;
		background-color: #fff;
		padding: 24px;
		border: 1px solid #e5e7eb;
		transition: 0.3s ease;
		@media screen and (max-width: 992px) {
			width: 100%;
			flex: 1 1 calc(50% - 60px);
		}
		@media screen and (max-width: 575px) {
			width: 100%;
			flex: 1 1 100%;
		}
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
		.components-input-control__container{
			background-color: #f0f0f0;
		}
		.components-input-control__backdrop{
			border-color: transparent !important;
		}
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
	.dataviews-view-table{
		tr{
			th{
				&:first-child{
					padding-left: 25px;
				}
				&:last-child{
					padding-right: 25px;
				}
			}
			td{
				&:first-child{
					padding-left: 25px;
				}
				&:last-child{
					padding-right: 25px;
				}
			}
		}
	}
	.dataviews__view-actions{
		padding: 16px 25px;
	}
	.dataviews-view-table__actions-column {
		padding: 30px 0;
	}
	.dataviews-wrapper{
		.components-h-stack{
			.components-dropdown{
				display: none;
			}
		}
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
		margin-bottom: 16px;
		border-bottom: 1px solid var(--directorist-color-border);
		padding-bottom: 16px;
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
	.directorist-repeater-table-wrapper {
		max-width: 600px;
		@media (max-width: 768px) {
			max-width: 500px;
		}
	}
	.formgent-editor-rating {
		display: flex;
		align-items: center;
		gap: 12px;
		margin: 5px 0;
	}
	.formgent-editor-rating__icons {
		display: flex;
		align-items: center;
		gap: 5px;
	}
	.formgent-editor-rating__icons
		.formgent-editor-rating__icon--active
		svg
		path {
		fill: var(--directorist-color-primary);
	}
	.formgent-google-map-external-link {
		color: var(--directorist-color-info);
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
		&.directorist-btn-disabled {
			opacity: 0.5;
			pointer-events: none;
		}
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
	.directorist-answers-section {
		display: flex;
		gap: 24px;
		flex-direction: column;
		align-items: flex-start;
	}

	.formgent-response-badge {
		background-color: var(--directorist-color-light);
		padding: 0 8px;
		color: var(--directorist-color-dark);
		font-size: 14px;
		line-height: 1.6;
		font-weight: 400;
		border-radius: 4px;
	}

	.formgent-response-table__drawer__tab__item {
		display: flex;
		gap: 12px;
		width: 100%;
		&.formgent-response-table__drawer__tab__item--tag {
			.formgent-response-table__drawer__tab__item__title {
				height: 40px;
			}
		}
		.formgent-response-table__drawer__tab__item__icon {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 40px;
			width: 40px;
			min-width: 40px;
			border-radius: 10px;
			color: var(--directorist-color-dark);
			background-color: var(--directorist-color-light);
		}
		.formgent-response-table__drawer__tab__item__content {
			display: flex;
			gap: 6px;
			flex-direction: column;
			flex: 1;
		}
		.formgent-response-table__drawer__tab__item__title {
			display: block;
			font-size: 15px;
			font-weight: 600;
			color: var(--formgent-color-dark);
			margin: 0;
			&:first-letter {
				text-transform: uppercase;
			}
		}
		.formgent-response-table__drawer__tab__item__desc {
			display: flex;
			gap: 5px;
			flex-wrap: wrap;
			font-size: 14px;
			font-weight: 400;
			color: var(--directorist-color-light-gray);
			margin: 0;
			img {
				max-width: 200px;
			}
			.formgent-signature-image-preview,
			.formgent-file-upload-preview {
				width: 100%;
				max-width: 350px;
			}
			.formgent-file-upload-answer {
				padding: 8px 0;
				background-color: #fff;
				display: flex;
				justify-content: space-between;
				align-items: center;
				width: 100%;
				box-sizing: border-box;
				&:last-child {
					border-bottom: 0 none;
					padding-bottom: 0;
				}
			}
			.formgent-file-upload-answer__info,
			.formgent-file-upload-answer__action{
				display: flex;
				align-items: center;
				gap: 12px;
			}
			.formgent-file-upload-answer__media{
				min-width: 40px;
				display: flex;
				align-items: center;
				justify-content: center;
				img{
					width: 40px;
					height: 40px;
					object-fit: cover;
					border-radius: 8px;
				}
				video{
					width: 40px;
					height: 40px;
					object-fit: cover;
					border-radius: 8px;
				}
				svg{
					width: 36px;
					height: 36px;
					path{
						fill: var(--directorist-color-light-gray);
					}
				}
			}
			.formgent-file-upload-answer__file-name{
				font-size: 13px;
				max-width: 230px;
				text-overflow: ellipsis;
				overflow: hidden;
				color: var(--directorist-color-light-gray);
				margin-bottom: 4px;
			}
			.formgent-file-upload-answer__file-size {
				font-size: 12px;
				color: var(--directorist-color-light-gray);
			}
			.formgent-file-upload-answer__download,
			.formgent-file-upload-answer__view{
				display: flex;
				align-items: center;
				justify-content: center;
				transition: 0.3s ease;
				cursor: pointer;
				svg{
					width: 20px;
					height: 20px;
					transition: 0.3s ease;
				}
			}
		}
		.formgent-response-table__drawer__tab__item__desc__key {
			font-size: 14px;
			font-weight: 500;
			display: inline-flex;
			padding-right: 4px;
			&::first-letter {
				text-transform: uppercase;
			}
		}
		.formgent-response-table__drawer__tab__item__desc__value {
			font-size: 14px;
			color: var(--directorist-color-light-gray);
		}
		.formgent-response-table__drawer__tab__item__add {
			display: flex;
			align-items: center;
			background: transparent;
			border: none;
			margin: 0;
			padding: 0;
			cursor: pointer;
			&:hover {
				color: var(--directorist-color-primary);
			}
		}
		.formgent-response-table__drawer__tab__item__btns {
			display: flex;
			gap: 8px;
			flex-wrap: wrap;
			.formgent-response-table__drawer__tab__item__btn {
				font-size: 14px;
				font-weight: 500;
				padding: 0 8px;
				margin: 0;
				height: 28px;
				color: var(--directorist-color-gray);
				background: var(--formgent-color-border);
				border: none;
				border-radius: 8px;
				cursor: pointer;
				transition: all ease 0.3s;
				&:hover {
					color: var(--formgent-color-white);
					background: var(--formgent-color-light-gray);
				}
			}
		}
	}
`;

export { EnquiriesComponentStyle, EnquiryDetailsModalStyle };

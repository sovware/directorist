import { Modal } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import styled from 'styled-components';

const ReplyOptions = styled.div`
	display: grid;
	gap: 16px;
	label {
		display: grid;
		gap: 6px;
		font-weight: 600;
	}
	input {
		width: 100%;
		box-sizing: border-box;
	}
	p {
		margin: 0;
	}
	.directorist-email-reply-actions {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
	}
`;

export default function EmailReplyModal({ reply, onClose }) {
	return (
		<Modal title={__('Reply by email', 'directorist')} onRequestClose={onClose}>
			<ReplyOptions>
				<label>
					{__('To', 'directorist')}
					<input type="text" readOnly value={reply.email} />
				</label>
				<label>
					{__('Subject', 'directorist')}
					<input type="text" readOnly value={reply.subject} />
				</label>
				<p>
					{__(
						'Choose where to write your reply. You will review and send it from your email account.',
						'directorist'
					)}
				</p>
				<div className="directorist-email-reply-actions">
					<a className="components-button is-primary" href={reply.mailto}>
						{__('Open email app', 'directorist')}
					</a>
					<a
						className="components-button is-secondary"
						href={reply.gmail}
						target="_blank"
						rel="noopener noreferrer"
					>
						{__('Open Gmail', 'directorist')}
					</a>
				</div>
				<p>
					{__(
						'If your email app does not open, use Gmail or copy the address and subject into your webmail.',
						'directorist'
					)}
				</p>
			</ReplyOptions>
		</Modal>
	);
}

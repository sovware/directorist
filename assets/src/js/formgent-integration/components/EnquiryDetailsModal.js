/**
 * WordPress dependencies
 */
import { Modal } from '@wordpress/components';
import { renderAnswerValue } from '../utils/renderAnswerValue';
import { EnquiryDetailsModalStyle } from './style';
import Reply from '../icons/Reply';
import Check from '../icons/Check';
import Trash from '../icons/Trash';

/**
 * EnquiryDetailsModal Component
 * Displays detailed information about an enquiry in a modal
 *
 * @param {Object} props - Component props
 * @param {boolean} props.isOpen - Whether the modal is open
 * @param {Object} props.selectedItem - The selected enquiry item
 * @param {Function} props.onClose - Function to call when modal should close
 * @param {Function} props.statusBadge - Function to get status badge class
 * @returns {JSX.Element} - The modal component
 */
export default function EnquiryDetailsModal({
	isOpen,
	selectedItem,
	onClose,
	statusBadge,
}) {
	// Function to render form answers
	const renderFormAnswers = (answers) => {
		if (!answers || !Array.isArray(answers)) {
			return <p>No answers available</p>;
		}

		return (
			<div className="directorist-enquiry-answers">
				{answers.map((answer, index) => (
					<div
						key={answer.id || index}
						className="directorist-enquiry-answer-item"
					>
						<h4 className="directorist-enquiry-answer-title">
							{answer.label || answer.field_name}
						</h4>
						<div className="directorist-enquiry-answer-value">
							{renderAnswerValue(answer)}
						</div>
					</div>
				))}
			</div>
		);
	};

	if (!isOpen || !selectedItem) {
		return null;
	}

	return (
		<Modal
			title="Enquiry Details"
			onRequestClose={onClose}
			className="directorist-enquiry-modal"
			size="large"
		>
			<EnquiryDetailsModalStyle className="directorist-enquiry-modal-content">
				<div className="directorist-enquiry-modal-info">
					<div className="directorist-enquiry-sender">
						<div className="directorist-enquiry-sender-avatar">
							<img
								src={selectedItem.sender_avatar}
								alt={selectedItem.sender_name}
							/>
						</div>
						<div className="directorist-enquiry-sender-info">
							<h2>
								{selectedItem.sender_name}
								<span
									className={`directorist-badge directorist-badge-${statusBadge(selectedItem.status)}`}
								>
									{selectedItem.status}
								</span>
							</h2>
							<p>{selectedItem.sender_email}</p>
							<span>{selectedItem.received_at}</span>
						</div>
					</div>
					<div className="directorist-enquiry-listing">
						<h3>Regarding Listing</h3>
						<a
							href={selectedItem.listing_url}
							target="_blank"
							rel="noopener noreferrer"
						>
							{selectedItem.listing_title}
						</a>
					</div>
				</div>

				<div className="directorist-answers-section">
					{renderFormAnswers(selectedItem.answers)}
				</div>

				<div className="directorist-enquiry-modal-footer">
					<button className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-reply">
						<Reply />
						Send Email
					</button>
					<button className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-resolved">
						<Check />
						Mark Resolved
					</button>
					<button className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-delete">
						<Trash />
						Delete
					</button>
				</div>
			</EnquiryDetailsModalStyle>
		</Modal>
	);
}

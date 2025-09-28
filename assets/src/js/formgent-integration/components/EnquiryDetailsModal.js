/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { Modal } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { renderAnswerValue } from '../utils/renderAnswerValue';
import { EnquiryDetailsModalStyle } from './style';
import Reply from '../icons/Reply';
import Check from '../icons/Check';
import Trash from '../icons/Trash';
import {
	fetchSingleEnquiry,
	findMatchingEnquiry,
	getStatusBadgeText,
} from '../utils/enquiryUtils';

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
	enquiries,
	handleMarkAsRead,
	handleDeleteItem,
	handleSendEmail,
}) {
	const [singleItem, setSingleItem] = useState(null);
	const [matchedEnquiry, setMatchedEnquiry] = useState(null);
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState(null);

	// Effect to fetch single item data when selectedItem changes
	useEffect(() => {
		if (!selectedItem) return;

		setLoading(true);
		setError(null);

		fetchSingleEnquiry(selectedItem)
			.then((data) => {
				setSingleItem(data);
				const matched = findMatchingEnquiry(data, enquiries);
				setMatchedEnquiry(matched);
			})
			.catch((err) => {
				setError('Failed to load enquiry details');
				console.error('Error loading enquiry:', err);
			})
			.finally(() => {
				setLoading(false);
			});
	}, [selectedItem]);

	// Separate effect to update matchedEnquiry when enquiries change (without re-fetching)
	useEffect(() => {
		if (!singleItem || !enquiries) return;

		const matched = findMatchingEnquiry(singleItem, enquiries);
		if (matched) {
			setMatchedEnquiry(matched);
		}
	}, [enquiries, singleItem?.response?.form_id]);

	// Function to handle mark as read with immediate UI update
	const handleMarkAsReadClick = () => {
		if (!singleItem?.response || singleItem.response.is_read === '1')
			return;

		// Update local state immediately for instant UI feedback
		setSingleItem((prevSingleItem) => ({
			...prevSingleItem,
			response: {
				...prevSingleItem.response,
				is_read: '1',
			},
		}));

		// Call the parent's handleMarkAsRead function
		handleMarkAsRead(singleItem.response);
	};

	// Function to render form answers
	const renderFormAnswers = () => {
		if (
			!singleItem ||
			!singleItem.fields ||
			!singleItem.response ||
			!singleItem.response.answers
		) {
			return <p>No answers available</p>;
		}

		const { fields, response } = singleItem;

		return (
			<div className="directorist-enquiry-answers">
				{fields.map((field, index) => {
					// Find matching answer by field name
					const matchingAnswer = response.answers.find(
						(answer) => answer.field_name === field.name
					);

					return (
						<div
							key={field.name || index}
							className="directorist-enquiry-answer-item"
						>
							<h4 className="directorist-enquiry-answer-title">
								{field.label}
							</h4>
							<div className="directorist-enquiry-answer-value">
								{matchingAnswer ? (
									renderAnswerValue(matchingAnswer)
								) : (
									<span className="directorist-no-answer">
										No answer provided
									</span>
								)}
							</div>
						</div>
					);
				})}
			</div>
		);
	};

	if (!isOpen || !selectedItem) {
		return null;
	}

	return (
		<Modal
			title={`Enquiry Details - ${matchedEnquiry?.listing_title || 'Unknown Listing'}`}
			onRequestClose={onClose}
			className="directorist-enquiry-modal"
			size="large"
		>
			<EnquiryDetailsModalStyle className="directorist-enquiry-modal-content">
				{loading && (
					<div className="directorist-loading">
						<p>Loading enquiry details...</p>
					</div>
				)}

				{error && (
					<div className="directorist-error">
						<p>{error}</p>
					</div>
				)}

				{!loading && !error && (
					<>
						<div className="directorist-enquiry-modal-info">
							<div className="directorist-enquiry-sender">
								<div className="directorist-enquiry-sender-avatar">
									<img
										src={
											matchedEnquiry?.user?.profile_url ||
											singleItem?.response?.user_email
										}
										alt={
											matchedEnquiry?.user
												?.display_name ||
											singleItem?.response?.username
										}
									/>
								</div>
								<div className="directorist-enquiry-sender-info">
									<h2>
										{matchedEnquiry?.user?.display_name ||
											singleItem?.response?.username}
										<span
											className={`directorist-badge directorist-badge-${statusBadge(singleItem?.response?.is_read)}`}
										>
											{getStatusBadgeText(
												singleItem?.response?.is_read
											)}
										</span>
									</h2>
									<p>
										{matchedEnquiry?.user?.user_email ||
											singleItem?.response?.user_email}
									</p>
									<span>
										{singleItem?.response?.created_at}
									</span>
								</div>
							</div>
							<div className="directorist-enquiry-listing">
								<h3>Regarding Listing</h3>
								<a
									href="#"
									target="_blank"
									rel="noopener noreferrer"
								>
									{matchedEnquiry?.listing_title ||
										'Unknown Listing'}
								</a>
							</div>
						</div>

						<div className="directorist-answers-section">
							{renderFormAnswers()}
						</div>

						<div className="directorist-enquiry-modal-footer">
							<button
								className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-reply"
								onClick={() =>
									handleSendEmail(singleItem?.response)
								}
							>
								<Reply />
								Send Email
							</button>
							<button
								className={`directorist-enquiry-modal-btn directorist-enquiry-modal-btn-resolved ${singleItem?.response?.is_read === '1' ? 'directorist-btn-disabled' : ''}`}
								onClick={handleMarkAsReadClick}
								disabled={singleItem?.response?.is_read === '1'}
							>
								<Check />
								{singleItem?.response?.is_read === '1'
									? 'Marked as read'
									: 'Mark as read'}
							</button>
							<button
								className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-delete"
								onClick={() => {
									handleDeleteItem(singleItem?.response);
									onClose();
								}}
							>
								<Trash />
								Delete
							</button>
						</div>
					</>
				)}
			</EnquiryDetailsModalStyle>
		</Modal>
	);
}

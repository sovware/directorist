/**
 * WordPress dependencies
 */
import { Modal } from '@wordpress/components';
import { useEffect, useState, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import ReactSVG from 'react-inlinesvg';

/**
 * Internal dependencies
 */
import Check from '../icons/Check';
import Reply from '../icons/Reply';
import Trash from '../icons/Trash';
import {
	fetchSingleEnquiry,
	findMatchingEnquiry,
	getStatusBadgeText,
	markEnquiryAsRead,
	formatRelativeDate,
} from '../utils/enquiryUtils';
import { EnquiryDetailsModalStyle, EnquiryModalGlobalStyle } from './style';

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
	box = 'receive',
	isOpen,
	selectedItem,
	onClose,
	statusBadge,
	enquiries,
	handleMarkAsRead,
	handleDeleteItem,
	handleSendEmail,
	handleTableRefresh,
}) {
	const [singleItem, setSingleItem] = useState(null);
	const [matchedEnquiry, setMatchedEnquiry] = useState(null);
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState(null);
	const hasMarkedAsReadRef = useRef(false);
	const directoristModules = window.formgent?.directorist_modules || {};
	const {
		TableDrawerAnswer,
		SingleResponseAnswer,
		getFormattedAnswer,
		handleAnswerIcon,
	} = directoristModules;
	const ResponseAnswer = TableDrawerAnswer || SingleResponseAnswer;

	const getFallbackAnswerValue = (answer) => {
		if (typeof getFormattedAnswer === 'function') {
			return getFormattedAnswer(answer);
		}

		return (
			answer?.value ||
			answer?.answer ||
			answer?.formatted_value ||
			answer?.display_value ||
			''
		);
	};

	const renderFallbackAnswer = (answer, index) => {
		const label =
			answer?.label ||
			answer?.title ||
			answer?.field?.label ||
			answer?.name ||
			__('Answer', 'directorist');
		const value = getFallbackAnswerValue(answer);
		const displayValue = Array.isArray(value) ? value.join(', ') : value;

		return (
			<div className="directorist-enquiry-answer-item" key={index}>
				<h4 className="directorist-enquiry-answer-title">{label}</h4>
				<p className="directorist-enquiry-answer-value">
					{displayValue || __('No answer provided', 'directorist')}
				</p>
			</div>
		);
	};

	// Effect to fetch single item data when selectedItem changes
	useEffect(() => {
		if (!selectedItem) return;

		// Reset the mark-as-read flag when a new item is selected
		hasMarkedAsReadRef.current = false;
		setLoading(true);
		setError(null);

		fetchSingleEnquiry(selectedItem, box)
			.then((data) => {
				if (!data?.success) throw new Error('Response not found');
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
	}, [selectedItem, box]);

	// Separate effect to update matchedEnquiry when enquiries change (without re-fetching)
	useEffect(() => {
		if (!singleItem || !enquiries) return;

		const matched = findMatchingEnquiry(singleItem, enquiries);
		if (matched) {
			setMatchedEnquiry(matched);
		}
	}, [enquiries, singleItem?.response?.form_id]);

	// Automatically mark as read when modal content loads
	useEffect(() => {
		if (
			box === 'send' ||
			!isOpen ||
			!singleItem?.response ||
			loading ||
			singleItem.response.is_read === '1' ||
			hasMarkedAsReadRef.current
		) {
			return;
		}

		// Mark that we've processed this item
		hasMarkedAsReadRef.current = true;

		// Update local state immediately for instant UI feedback
		setSingleItem((prevSingleItem) => ({
			...prevSingleItem,
			response: {
				...prevSingleItem.response,
				is_read: '1',
			},
		}));

		// Call markEnquiryAsRead with silent=true to suppress toast
		markEnquiryAsRead(
			singleItem.response,
			handleTableRefresh || (() => {}),
			true
		);
	}, [box, isOpen, singleItem?.response?.id, loading, handleTableRefresh]);

	// Function to handle mark as read with immediate UI update
	const handleMarkAsReadClick = () => {
		if (!singleItem?.response || singleItem.response.is_read === '1') return;

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

	if (!isOpen || !selectedItem) {
		return null;
	}

	return (
		<>
			<EnquiryModalGlobalStyle />
			<Modal
				title={`Enquiry Details - ${matchedEnquiry?.listing_title || 'Unknown Listing'}`}
				onRequestClose={onClose}
				className="directorist-enquiry-modal"
				size="large"
				isDismissible={false}
			>
				{/* Positioned in the header via global CSS. */}
				<button
					type="button"
					className="directorist-enquiry-modal-close"
					onClick={onClose}
					aria-label={__('Close enquiry details', 'directorist')}
					title={__('Close enquiry details', 'directorist')}
				>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 24 24"
						width="24"
						height="24"
						aria-hidden="true"
						focusable="false"
					>
						<path d="m13.06 12 6.47-6.47-1.06-1.06L12 10.94 5.53 4.47 4.47 5.53 10.94 12l-6.47 6.47 1.06 1.06L12 13.06l6.47 6.47 1.06-1.06L13.06 12Z" />
					</svg>
				</button>
				<EnquiryDetailsModalStyle className="directorist-enquiry-modal-content">
					{loading && (
						<div className="directorist-loading">
							<p>{__('Loading enquiry details...', 'directorist')}</p>
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
												box === 'send'
													? matchedEnquiry?.recipient?.profile_url
													: matchedEnquiry?.user?.profile_url
											}
											alt={
												box === 'send'
													? matchedEnquiry?.recipient?.display_name
													: matchedEnquiry?.user?.display_name
											}
										/>
									</div>
									<div className="directorist-enquiry-sender-info">
										<h2>
											{box === 'send'
												? matchedEnquiry?.recipient?.display_name
												: matchedEnquiry?.user?.display_name ||
													singleItem?.response?.username}
											<span
												className={`directorist-badge directorist-badge-${box === 'send' ? 'primary' : statusBadge(singleItem?.response?.is_read)}`}
											>
												{box === 'send'
													? __('Submitted', 'directorist')
													: getStatusBadgeText(singleItem?.response?.is_read)}
											</span>
										</h2>
										{box === 'receive' && (
											<p>
												{matchedEnquiry?.user?.user_email ||
													singleItem?.response?.user_email}
											</p>
										)}
										<span>
											{formatRelativeDate(singleItem?.response?.created_at)}
										</span>
									</div>
								</div>
								<div className="directorist-enquiry-listing">
									<h3>{__('Regarding Listing', 'directorist')}</h3>
									<a
										href={singleItem?.listing_permalink || '#'}
										target="_blank"
										rel="noopener noreferrer"
									>
										{matchedEnquiry?.listing_title ||
											__('Unknown Listing', 'directorist')}
									</a>
								</div>
							</div>

							<div className="directorist-answers-section">
								{singleItem?.response?.answers.map((answer, index) => {
									if (ResponseAnswer) {
										return (
											<ResponseAnswer
												key={index}
												answer={answer}
												handleAnswerIcon={handleAnswerIcon}
												getFormattedAnswer={getFormattedAnswer}
												ReactSVG={ReactSVG}
												useState={useState}
												useEffect={useEffect}
												isLoadedFromDirectorist={true}
											/>
										);
									}

									return renderFallbackAnswer(answer, index);
								})}
							</div>

							{box === 'receive' && (
								<div className="directorist-enquiry-modal-footer">
									<button
										className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-reply"
										aria-label={__('Send Email', 'directorist')}
										title={__('Send Email', 'directorist')}
										onClick={() => handleSendEmail(singleItem?.response)}
									>
										<Reply />
										<span>{__('Send Email', 'directorist')}</span>
									</button>
									<button
										className={`directorist-enquiry-modal-btn directorist-enquiry-modal-btn-resolved ${singleItem?.response?.is_read === '1' ? 'directorist-btn-disabled' : ''}`}
										aria-label={
											singleItem?.response?.is_read === '1'
												? __('Marked as read', 'directorist')
												: __('Mark as read', 'directorist')
										}
										title={
											singleItem?.response?.is_read === '1'
												? __('Marked as read', 'directorist')
												: __('Mark as read', 'directorist')
										}
										onClick={handleMarkAsReadClick}
										disabled={singleItem?.response?.is_read === '1'}
									>
										<Check />
										<span>
											{singleItem?.response?.is_read === '1'
												? __('Marked as read', 'directorist')
												: __('Mark as read', 'directorist')}
										</span>
									</button>
									<button
										className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-delete"
										aria-label={__('Delete', 'directorist')}
										title={__('Delete', 'directorist')}
										onClick={() => {
											handleDeleteItem(singleItem?.response);
											onClose();
										}}
									>
										<Trash />
										<span>{__('Delete', 'directorist')}</span>
									</button>
								</div>
							)}
						</>
					)}
				</EnquiryDetailsModalStyle>
			</Modal>
		</>
	);
}

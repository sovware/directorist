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
	const {
		TableDrawerAnswer,
		getFormattedAnswer,
		handleAnswerIcon,
		isProActive,
	} = window.formgent.directorist_modules;

	// Effect to fetch single item data when selectedItem changes
	useEffect(() => {
		if (!selectedItem) return;

		// Reset the mark-as-read flag when a new item is selected
		hasMarkedAsReadRef.current = false;
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

	// Automatically mark as read when modal content loads
	useEffect(() => {
		if (
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
	}, [isOpen, singleItem?.response?.id, loading, handleTableRefresh]);

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
				{/* Custom close button without tooltip - positioned in header via global CSS */}
				<button
					type="button"
					className="directorist-enquiry-modal-close"
					onClick={onClose}
					aria-label=""
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
							<p>
								{__(
									'Loading enquiry details...',
									'directorist'
								)}
							</p>
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
												matchedEnquiry?.user
													?.profile_url ||
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
											{matchedEnquiry?.user
												?.display_name ||
												singleItem?.response?.username}
											<span
												className={`directorist-badge directorist-badge-${statusBadge(singleItem?.response?.is_read)}`}
											>
												{getStatusBadgeText(
													singleItem?.response
														?.is_read
												)}
											</span>
										</h2>
										<p>
											{matchedEnquiry?.user?.user_email ||
												singleItem?.response
													?.user_email}
										</p>
										<span>
											{formatRelativeDate(
												singleItem?.response?.created_at
											)}
										</span>
									</div>
								</div>
								<div className="directorist-enquiry-listing">
									<h3>
										{__('Regarding Listing', 'directorist')}
									</h3>
									<a
										href={
											singleItem?.listing_permalink || '#'
										}
										target="_blank"
										rel="noopener noreferrer"
									>
										{matchedEnquiry?.listing_title ||
											__(
												'Unknown Listing',
												'directorist'
											)}
									</a>
								</div>
							</div>

							<div className="directorist-answers-section">
								{singleItem?.response?.answers.map(
									(answer, index) => {
										return (
											<TableDrawerAnswer
												key={index}
												answer={answer}
												handleAnswerIcon={
													handleAnswerIcon
												}
												getFormattedAnswer={
													getFormattedAnswer
												}
												ReactSVG={ReactSVG}
												useState={useState}
												useEffect={useEffect}
												isLoadedFromDirectorist={true}
											/>
										);
									}
								)}
							</div>

							<div className="directorist-enquiry-modal-footer">
								<button
									className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-reply"
									onClick={() =>
										handleSendEmail(singleItem?.response)
									}
								>
									<Reply />
									<span>
										{__('Send Email', 'directorist')}
									</span>
								</button>
								<button
									className={`directorist-enquiry-modal-btn directorist-enquiry-modal-btn-resolved ${singleItem?.response?.is_read === '1' ? 'directorist-btn-disabled' : ''}`}
									onClick={handleMarkAsReadClick}
									disabled={
										singleItem?.response?.is_read === '1'
									}
								>
									<Check />
									<span>
										{singleItem?.response?.is_read === '1'
											? __(
													'Marked as read',
													'directorist'
												)
											: __('Mark as read', 'directorist')}
									</span>
								</button>
								<button
									className="directorist-enquiry-modal-btn directorist-enquiry-modal-btn-delete"
									onClick={() => {
										handleDeleteItem(singleItem?.response);
										onClose();
									}}
								>
									<Trash />
									<span>{__('Delete', 'directorist')}</span>
								</button>
							</div>
						</>
					)}
				</EnquiryDetailsModalStyle>
			</Modal>
		</>
	);
}

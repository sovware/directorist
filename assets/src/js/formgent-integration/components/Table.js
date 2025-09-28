/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * External dependencies
 */
import { Table } from '@wpmvc/components';
import CheckIcon from '../icons/Check';

/**
 * Internal dependencies
 */
import EnquiryDetailsModal from './EnquiryDetailsModal';
import {
	markEnquiryAsRead,
	deleteEnquiry,
	sendEmailToUser,
	getStatusBadgeClass,
} from '../utils/enquiryUtils';

export default function Tables(props) {
	const { items, handleTableRefresh } = props;
	const [perPage, setPerPage] = useState(10);
	const [searchTerm, setSearchTerm] = useState('');
	const [currentPage, setCurrentPage] = useState(1);
	const [currentItems, setCurrentItems] = useState([]);
	const [isViewModalOpen, setIsViewModalOpen] = useState(false);
	const [selectedItem, setSelectedItem] = useState(null);

	// Filter items based on search term
	const filterItems = (search = '') => {
		let filteredItems = items || [];

		// Filter by search term
		if (search && search.trim() !== '') {
			const query = search.toLowerCase().trim();
			filteredItems = filteredItems.filter((item) => {
				return (
					item.title?.toLowerCase().includes(query) ||
					item.type?.toLowerCase().includes(query) ||
					item.username?.toLowerCase().includes(query) ||
					item.id?.toString().includes(query)
				);
			});
		}

		return filteredItems;
	};

	// Handle refresh with search and pagination
	const handleRefresh = (params) => {
		setSearchTerm(params.search || '');
		setCurrentPage(params.page || 1);
		setPerPage(params.perPage || 10);
	};

	// Update items when items or searchTerm changes
	useEffect(() => {
		const filteredItems = filterItems(searchTerm);
		setCurrentItems(filteredItems);
		setCurrentPage(1); // Reset to first page when search changes
	}, [items, searchTerm]);

	// Calculate paginated items
	const startIndex = (currentPage - 1) * perPage;
	const endIndex = startIndex + perPage;
	const paginatedItems = currentItems.slice(startIndex, endIndex);
	const totalItems = currentItems.length;

	// switch case for status badge
	const statusBadge = (status) => {
		const badgeClass = getStatusBadgeClass(status);
		switch (badgeClass) {
			case 'read':
				return 'primary';
			case 'unread':
				return 'warning';
			default:
				return 'primary';
		}
	};

	function getEnquiryStats(status) {
		return status === '0' ? 'new' : 'read';
	}

	const fields = [
		{
			id: 'enquiry',
			label: 'Enquiry',
			render: ({ item }) => {
				return (
					<div className="directorist-table-enquiry">
						<h2>{item.title}</h2>
						<p>{item.enquiry_prefix}</p>

						<div className="directorist-table-enquiry-action">
							<a
								href="#"
								className="directorist-table-enquiry-view"
								onClick={(e) => {
									e.preventDefault();
									setSelectedItem(item.id);
									setIsViewModalOpen(true);
								}}
							>
								View
							</a>
							<a
								href="#"
								className="directorist-table-enquiry-send-email"
								onClick={(e) => {
									e.preventDefault();
									handleSendEmail(item);
								}}
							>
								Send Email
							</a>
						</div>
					</div>
				);
			},
		},
		{
			id: 'listing',
			label: 'Listing',
			render: ({ item }) => {
				return (
					<div className="directorist-table-enquiry-listing">
						<h2>{item.listing_title}</h2>
						<span>{item.created_at}</span>
					</div>
				);
			},
		},
		{
			id: 'sender',
			label: 'Sender',
			render: ({ item }) => {
				return (
					<div className="directorist-table-enquiry-sender">
						<div className="directorist-table-enquiry-sender-avatar">
							<img
								src={item.user.profile_url}
								alt={item.user.display_name}
							/>
						</div>
						<div className="directorist-table-enquiry-sender-info">
							<h2>{item.user.display_name}</h2>
							<p>{item.user.user_email}</p>
						</div>
					</div>
				);
			},
		},
		{
			id: 'status',
			label: 'Status',
			render: ({ item }) => {
				return (
					<div className="directorist-table-enquiry-status">
						<span
							className={`directorist-badge directorist-badge-${statusBadge(item.is_read)}`}
						>
							{getEnquiryStats(item.is_read)}
						</span>
					</div>
				);
			},
		},
	];

	// Simplified handler functions using utility functions
	const handleMarkAsRead = (item) => {
		markEnquiryAsRead(item, handleTableRefresh);
	};

	const handleDeleteItem = (item) => {
		deleteEnquiry(item, handleTableRefresh);
	};

	const handleSendEmail = (item) => {
		sendEmailToUser(item);
	};

	function handleCancelDeleteAlert(item) {
		item.closeModal();
	}

	return (
		<>
			<Table
				actions={[
					{
						RenderModal: (item) => {
							return (
								<div className="directorist-formgent-table-modal">
									<h1>Are you sure to delete this item?</h1>
									<p>This action cannot be undone.</p>
									<div className="directorist-formgent-table-modal-action">
										<button
											onClick={() =>
												handleDeleteItem(item)
											}
											className="directorist-btn directorist-btn-danger"
										>
											Delete
										</button>
										<button
											onClick={() =>
												handleCancelDeleteAlert(item)
											}
											className="directorist-btn directorist-btn-light"
										>
											Cancel
										</button>
									</div>
								</div>
							);
						},
						hideModalHeader: true,
						id: 'delete',
						label: 'Delete',
						modalFocusOnMount: 'firstContentElement',
						supportsBulk: false,
					},
					{
						callback: (item) => {
							handleMarkAsRead(item);
						},
						id: 'mark-as-read',
						icon: <CheckIcon />,
						label: 'Mark as read',
						supportsBulk: false,
						isEligible: (item) => {
							return item.is_read === '0';
						},
					},
				]}
				items={paginatedItems}
				total={totalItems}
				isLoading={false}
				titleField={'enquiry'}
				layoutType={'table'}
				layout={'table'}
				layouts={[]}
				refresh={handleRefresh}
				queryParams={{
					page: currentPage,
					perPage: perPage,
					search: searchTerm,
					sort: {
						field: 'enquiry',
						order: 'asc',
					},
				}}
				fields={fields}
				isItemClickable={() => {}}
				onChangeView={() => {}}
				renderItemLink={() => {}}
				view={{
					descriptionField: 'enquiry',
					fields: ['enquiry'],
					filters: [
						{ field: 'enquiry', operator: 'is', value: 2 },
						{
							field: 'status',
							operator: 'isAny',
							value: ['new', 'read', 'resolved'],
						},
					],
				}}
			/>

			{/* View Answers Modal */}
			<EnquiryDetailsModal
				isOpen={isViewModalOpen}
				selectedItem={selectedItem}
				onClose={() => {
					setIsViewModalOpen(false);
					setSelectedItem(null);
				}}
				statusBadge={statusBadge}
				enquiries={items}
				handleMarkAsRead={handleMarkAsRead}
				handleDeleteItem={handleDeleteItem}
				handleSendEmail={handleSendEmail}
			/>
		</>
	);
}

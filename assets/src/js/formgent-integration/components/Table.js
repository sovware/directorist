/**
 * WordPress dependencies
 */
import { useState, useMemo, useCallback } from '@wordpress/element';
import { DataViews } from '@wordpress/dataviews';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
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
	const { items = [], handleTableRefresh } = props;
	const [isViewModalOpen, setIsViewModalOpen] = useState(false);
	const [selectedItem, setSelectedItem] = useState(null);

	// Initialize view state for DataViews
	const [view, setView] = useState({
		type: 'table',
		search: '',
		page: 1,
		perPage: 10,
		sort: {
			field: 'created_at',
			direction: 'desc',
		},
		fields: ['id', 'enquiry', 'listing', 'sender', 'status'],
		layout: {},
	});

	// Get badge variant based on status
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

	// Get enquiry status text
	const getEnquiryStatusText = (status) => {
		return status === '0' ? 'new' : 'read';
	};

	// Define fields configuration for DataViews
	const fields = useMemo(
		() => [
			{
				id: 'id',
				header: 'ID',
				enableHiding: true,
				enableSorting: true,
				render: ({ item }) => {
					return (
						<div className="directorist-table-enquiry-id">
							<span>#{item.id}</span>
						</div>
					);
				},
			},
			{
				id: 'enquiry',
				header: 'Enquiry',
				enableHiding: false,
				enableSorting: false,
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
									{__('View', 'directorist')}
								</a>
								<a
									href="#"
									className="directorist-table-enquiry-send-email"
									onClick={(e) => {
										e.preventDefault();
										handleSendEmail(item);
									}}
								>
									{__('Send Email', 'directorist')}
								</a>
							</div>
						</div>
					);
				},
			},
			{
				id: 'listing',
				header: 'Listing',
				enableHiding: true,
				enableSorting: false,
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
				header: 'Sender',
				enableHiding: true,
				enableSorting: false,
				render: ({ item }) => {
					return (
						<div className="directorist-table-enquiry-sender">
							<div className="directorist-table-enquiry-sender-avatar">
								<img
									src={item.user?.profile_url}
									alt={item.user?.display_name}
								/>
							</div>
							<div className="directorist-table-enquiry-sender-info">
								<h2>{item.user?.display_name}</h2>
								<p>{item.user?.user_email}</p>
							</div>
						</div>
					);
				},
			},
			{
				id: 'status',
				header: 'Status',
				enableHiding: true,
				enableSorting: false,
				render: ({ item }) => {
					return (
						<div className="directorist-table-enquiry-status">
							<span
								className={`directorist-badge directorist-badge-${statusBadge(
									item.is_read
								)}`}
							>
								{getEnquiryStatusText(item.is_read)}
							</span>
						</div>
					);
				},
			},
		],
		[statusBadge, getEnquiryStatusText]
	);

	// Handler functions using utility functions
	const handleMarkAsRead = useCallback(
		(item) => {
			if (!item) return;
			markEnquiryAsRead(item, handleTableRefresh);
		},
		[handleTableRefresh]
	);

	const handleDeleteItem = useCallback(
		(items) => {
			if (!items || items.length === 0) return;
			const item = Array.isArray(items) ? items[0] : items;
			deleteEnquiry(item, handleTableRefresh);
		},
		[handleTableRefresh]
	);

	const handleSendEmail = useCallback((item) => {
		if (!item) return;
		sendEmailToUser(item);
	}, []);

	const handleOpenDeleteModal = useCallback((items) => {
		if (!items || items.length === 0) return;
		const item = Array.isArray(items) ? items[0] : items;
		setItemToDelete(item);
		setIsDeleteModalOpen(true);
	}, []);

	const handleCancelDelete = useCallback((item) => {
		if (!item) return;
		item.closeModal();
	}, []);

	// Define actions for DataViews
	const actions = useMemo(
		() => [
			{
				id: 'mark-as-read',
				label: __('Mark as read', 'directorist'),
				isPrimary: false,
				icon: <CheckIcon />,
				callback: (items) => {
					const item = Array.isArray(items) ? items[0] : items;
					handleMarkAsRead(item);
				},
				isEligible: (item) => {
					return item.is_read === '0';
				},
			},
			{
				RenderModal: (item) => {
					return (
						<div className="directorist-formgent-table-modal">
							<h1>{__('Are you sure to delete this item?', 'directorist')}</h1>
							<p>{__('This action cannot be undone.', 'directorist')}</p>
							<div className="directorist-formgent-table-modal-action">
								<button
									onClick={() =>
										handleDeleteItem(item)
									}
									className="directorist-btn directorist-btn-danger"
								>
									{__('Delete', 'directorist')}
								</button>
								<button
									onClick={() =>
										handleCancelDelete(item)
									}
									className="directorist-btn directorist-btn-light"
								>
									{__('Cancel', 'directorist')}
								</button>
							</div>
						</div>
					);
				},
				hideModalHeader: true,
				id: 'delete',
				label: __('Delete', 'directorist'),
				modalFocusOnMount: 'firstContentElement',
				supportsBulk: false,
			},
		],
		[handleMarkAsRead, handleOpenDeleteModal]
	);

	// Filter and search items
	const filteredData = useMemo(() => {
		if (!Array.isArray(items)) return [];

		let filtered = [...items];

		// Apply search filter
		if (view.search && view.search.trim() !== '') {
			const query = view.search.toLowerCase().trim();
			filtered = filtered.filter((item) => {
				return (
					item.id?.toString().includes(query) ||
					item.listing_title?.toLowerCase().includes(query) ||
					item.user?.display_name?.toLowerCase().includes(query) ||
					item.user?.user_email?.toLowerCase().includes(query)
				);
			});
		}

		return filtered;
	}, [items, view.search]);

	// Handle view changes
	const handleChangeView = (newView) => {
		setView((prevView) => ({
			...prevView,
			...newView,
		}));
	};

	// Calculate pagination
	const paginatedData = useMemo(() => {
		const start = (view.page - 1) * view.perPage;
		const end = start + view.perPage;
		return filteredData.slice(start, end);
	}, [filteredData, view.page, view.perPage]);

	const totalItems = filteredData.length;
	const totalPages = Math.ceil(totalItems / view.perPage);

	return (
		<>
			<DataViews
				data={paginatedData}
				fields={fields}
				view={view}
				onChangeView={handleChangeView}
				actions={actions}
				paginationInfo={{
					totalItems: totalItems,
					totalPages: totalPages,
				}}
				defaultLayouts={{
					table: {
						layout: {
							styles: {},
						},
					},
				}}
			/>

			{/* View Enquiry Details Modal */}
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
				handleDeleteItem={(item) => {
					if (!item) return;
					deleteEnquiry(item, handleTableRefresh);
				}}
				handleSendEmail={handleSendEmail}
			/>
		</>
	);
}

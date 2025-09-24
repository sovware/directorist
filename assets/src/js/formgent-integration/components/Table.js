/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { Table } from '@wpmvc/components';
import CheckIcon from '../icons/Check';
import TrashIcon from '../icons/Trash';
import EnquiryDetailsModal from './EnquiryDetailsModal';

export default function Tables({ items }) {
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
		switch (status) {
			case 'new':
				return 'warning';
			case 'read':
				return 'primary';
			case 'resolved':
				return 'success';
			default:
				return 'primary';
		}
	};

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
									setSelectedItem(item);
									setIsViewModalOpen(true);
								}}
							>
								View
							</a>
							<a
								href="#"
								className="directorist-table-enquiry-send-email"
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
						<span>{item.received_at}</span>
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
								src={item.sender_avatar}
								alt={item.sender_name}
							/>
						</div>
						<div className="directorist-table-enquiry-sender-info">
							<h2>{item.sender_name}</h2>
							<p>{item.sender_email}</p>
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
							className={`directorist-badge directorist-badge-${statusBadge(item.status)}`}
						>
							{item.status}
						</span>
					</div>
				);
			},
		},
	];

	return (
		<>
			<Table
				actions={[
					{
						RenderModal: () => {
							return (
								<div>
									<h1>Item masked as read</h1>
								</div>
							);
						},
						hideModalHeader: true,
						icon: <CheckIcon />,
						id: 'mark-as-read',
						isPrimary: true,
						label: 'Mark as read',
						modalFocusOnMount: 'firstContentElement',
						supportsBulk: true,
					},
					{
						RenderModal: () => {
							return (
								<div>
									<h1>Are you sure to delete this item?</h1>
								</div>
							);
						},
						hideModalHeader: false,
						icon: <TrashIcon />,
						id: 'delete',
						isPrimary: true,
						label: 'Delete',
						modalFocusOnMount: 'firstContentElement',
						supportsBulk: true,
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
			/>
		</>
	);
}

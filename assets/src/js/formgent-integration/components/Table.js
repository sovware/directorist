/**
 * WordPress dependencies
 */
import {
	useState,
	useMemo,
	useCallback,
	useEffect,
	useRef,
} from '@wordpress/element';
import { DataViews } from '@wordpress/dataviews';

/**
 * External dependencies
 */
import CheckIcon from '../icons/Check';
import TrashIcon from '../icons/Trash';
import { t, tsprintf } from '../utils/i18n';

/**
 * Internal dependencies
 */
import EnquiryDetailsModal from './EnquiryDetailsModal';
import {
	markEnquiryAsRead,
	bulkMarkEnquiriesAsRead,
	deleteEnquiry,
	bulkDeleteEnquiries,
	sendEmailToUser,
	getStatusBadgeClass,
	extractEnquiryTitleAndPrefix,
	enrichEnquiriesWithAnswers,
	formatRelativeDate,
} from '../utils/enquiryUtils';

export default function Tables(props) {
	const { items = [], handleTableRefresh } = props;
	const [isViewModalOpen, setIsViewModalOpen] = useState(false);
	const [selectedItem, setSelectedItem] = useState(null);
	const [enrichedItems, setEnrichedItems] = useState([]);
	const [isLoadingAnswers, setIsLoadingAnswers] = useState(false);
	const answersCacheRef = useRef(new Map()); // Cache for answers data
	const dataViewsRef = useRef(null);

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
		fields: ['enquiry', 'listing', 'sender', 'status'],
		layout: {},
	});

	// Get badge variant based on status
	const statusBadge = (status) => {
		const badgeClass = getStatusBadgeClass(status);
		switch (badgeClass) {
			case 'read':
				return 'success';
			case 'unread':
				return 'info';
			default:
				return 'primary';
		}
	};

	// Get enquiry status text
	const getEnquiryStatusText = (status) => {
		return status === '0'
			? t('status_new', 'New')
			: t('status_read', 'Read');
	};

	// Define fields configuration for DataViews
	const fields = useMemo(
		() => [
			{
				id: 'enquiry',
				header: t('enquiry', 'Enquiry'),
				enableHiding: false,
				enableSorting: false,
				render: ({ item }) => {
					const { title, prefix } =
						extractEnquiryTitleAndPrefix(item);
					//allow max 20 words in prefix
					const maxWords = 20;
					const words = prefix.split(' ');
					const truncatedPrefix = words.slice(0, maxWords).join(' ');
					return (
						<div className="directorist-table-enquiry">
							{title && <h2>{title}</h2>}
							{prefix && <p>{truncatedPrefix}...</p>}

							<div className="directorist-table-enquiry-action">
								<a
									href="#"
									className="directorist-table-enquiry-view"
									onClick={(e) => {
										e.preventDefault();
										e.stopPropagation();
										setSelectedItem(item.id);
										setIsViewModalOpen(true);
									}}
								>
									{t('view', 'View')}
								</a>
								<a
									href="#"
									className="directorist-table-enquiry-send-email"
									onClick={(e) => {
										e.preventDefault();
										e.stopPropagation();
										handleSendEmail(item);
									}}
								>
									{t('send_email', 'Send Email')}
								</a>
							</div>
						</div>
					);
				},
			},
			{
				id: 'listing',
				header: t('listing', 'Listing'),
				enableHiding: true,
				enableSorting: false,
				render: ({ item }) => {
					return (
						<div className="directorist-table-enquiry-listing">
							<h2>{item.listing_title}</h2>
							<span>{formatRelativeDate(item.created_at)}</span>
						</div>
					);
				},
			},
			{
				id: 'sender',
				header: t('sender', 'Sender'),
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
				header: t('status', 'Status'),
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
	const hasBulk = items.length > 1;
	const actions = useMemo(
		() => [
			{
				id: 'mark-as-read',
				label: t('mark_as_read', 'Mark as read'),
				supportsBulk: hasBulk,
				icon: <CheckIcon />,
				callback: (items) => {
					const itemsArray = Array.isArray(items) ? items : [items];
					if (itemsArray.length > 1) {
						bulkMarkEnquiriesAsRead(itemsArray, handleTableRefresh);
					} else {
						handleMarkAsRead(itemsArray[0]);
					}
				},
				isEligible: (item) => {
					return item.is_read === '0';
				},
			},
			{
				RenderModal: ({ items, closeModal }) => {
					return (
						<div className="directorist-formgent-table-modal">
							<h1>
								{items.length > 1
									? tsprintf(
											'confirm_delete_multiple_items',
											'Are you sure to delete %d items?',
											items.length
										)
									: t(
											'confirm_delete_single_item',
											'Are you sure to delete this item?'
										)}
							</h1>
							<p>
								{t(
									'action_cannot_be_undone',
									'This action cannot be undone.'
								)}
							</p>
							<div className="directorist-formgent-table-modal-action">
								<button
									onClick={() => {
										bulkDeleteEnquiries(
											items,
											handleTableRefresh
										);
										closeModal();
									}}
									className="directorist-btn directorist-btn-danger"
								>
									{t('delete', 'Delete')}
								</button>
								<button
									onClick={closeModal}
									className="directorist-btn directorist-btn-light"
								>
									{t('cancel', 'Cancel')}
								</button>
							</div>
						</div>
					);
				},
				hideModalHeader: true,
				id: 'delete',
				label: t('delete', 'Delete'),
				icon: <TrashIcon />,
				isDestructive: true,
				modalFocusOnMount: 'firstContentElement',
				supportsBulk: hasBulk,
			},
		],
		[handleMarkAsRead, handleOpenDeleteModal, hasBulk]
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
					item.listing_title?.toLowerCase().includes(query) ||
					item.user?.display_name?.toLowerCase().includes(query) ||
					item.user?.user_email?.toLowerCase().includes(query)
				);
			});
		}

		return [...filtered].reverse();
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

	// Lazy load answers only for visible/paginated items
	useEffect(() => {
		const container = dataViewsRef.current;

		if (!container) {
			return undefined;
		}

		const actionLabel = t('actions', 'Actions');
		const updateInternalLabels = () => {
			container
				.querySelectorAll('.dataviews-view-table-header')
				.forEach((element) => {
					if (element.textContent.trim() === 'Actions') {
						element.textContent = actionLabel;
					}
				});

			container
				.querySelectorAll('button[aria-label="Actions"]')
				.forEach((element) => {
					element.setAttribute('aria-label', actionLabel);
				});
		};

		updateInternalLabels();

		const observer = new MutationObserver(updateInternalLabels);
		observer.observe(container, { childList: true, subtree: true });

		return () => observer.disconnect();
	}, [view, actions]);

	useEffect(() => {
		if (paginatedData.length === 0) {
			return;
		}

		// Check if we need to fetch answers for any visible items
		const needsFetch = paginatedData.some(
			(item) =>
				item.id &&
				!answersCacheRef.current.has(String(item.id)) &&
				!item.answers
		);

		if (!needsFetch) {
			// All visible items are cached, just merge them
			const merged = paginatedData.map((item) => {
				const cachedAnswers = answersCacheRef.current.get(
					String(item.id)
				);
				return cachedAnswers
					? { ...item, answers: cachedAnswers }
					: item;
			});
			setEnrichedItems(merged);
			return;
		}

		// Fetch answers for visible items that aren't cached
		setIsLoadingAnswers(true);
		enrichEnquiriesWithAnswers(paginatedData, answersCacheRef.current)
			.then(({ enrichedItems: enriched, cache }) => {
				answersCacheRef.current = cache; // Update cache reference
				setEnrichedItems(enriched);
			})
			.catch((error) => {
				console.error('Error enriching items:', error);
				setEnrichedItems(paginatedData);
			})
			.finally(() => {
				setIsLoadingAnswers(false);
			});
	}, [paginatedData]);

	// Update enriched items when items change (but don't refetch if already cached)
	useEffect(() => {
		// Merge cached answers with items
		const merged = items.map((item) => {
			const cachedAnswers = answersCacheRef.current.get(String(item.id));
			return cachedAnswers ? { ...item, answers: cachedAnswers } : item;
		});
		setEnrichedItems(merged);
	}, [items]);

	const totalItems = filteredData.length;
	const totalPages = Math.ceil(totalItems / view.perPage);

	// Use enriched items for rendering
	const displayData = useMemo(() => {
		// Merge paginated data with enriched items by ID
		return paginatedData.map((item) => {
			const enriched = enrichedItems.find((e) => e.id === item.id);
			return enriched || item;
		});
	}, [paginatedData, enrichedItems]);

	return (
		<>
			<div ref={dataViewsRef}>
				<DataViews
					data={displayData}
					fields={fields}
					view={view}
					onChangeView={handleChangeView}
					actions={actions}
					getItemId={(item) => String(item.id)}
					search
					searchLabel={t('search_enquiries', 'Search enquiries...')}
					empty={t('no_results', 'No results')}
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
			</div>

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
				handleTableRefresh={handleTableRefresh}
			/>
		</>
	);
}

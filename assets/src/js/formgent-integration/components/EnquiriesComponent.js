/**
 * WordPress dependencies
 */
import { useEffect, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import Calendar from '../icons/Calendar';
import Check from '../icons/Check';
import Envelope from '../icons/Envelope';
import Inbox from '../icons/Inbox';
import { fetchAllEnquiries, fetchEnquiryKPIs } from '../utils/enquiryUtils';
import { EnquiriesComponentStyle } from './style';
import Tables from './Table';

const getVisibleFields = (box, type) => {
	if (type === 'list') {
		return box === 'send' ? ['recipient'] : ['sender', 'status'];
	}
	return box === 'send'
		? ['enquiry', 'listing', 'recipient']
		: ['enquiry', 'listing', 'sender', 'status'];
};

const EnquiriesComponent = ({ data = {} }) => {
	const [responseKPIs, setResponseKPIs] = useState(null);
	const [responses, setResponses] = useState([]);
	const [box, setBox] = useState('receive');
	const [total, setTotal] = useState(0);
	const [loading, setLoading] = useState(true);
	const [loadedBox, setLoadedBox] = useState(null);
	const [error, setError] = useState('');
	const [revision, setRevision] = useState(0);
	const [view, setView] = useState({
		type: 'table',
		search: '',
		page: 1,
		perPage: 10,
		sort: { field: 'created_at', direction: 'desc' },
		fields: ['enquiry', 'listing', 'sender', 'status'],
		layout: {},
	});
	const strings = data?.strings || {};

	useEffect(() => {
		const media = window.matchMedia('(max-width: 767px)');
		const updateLayout = () => {
			const type = media.matches ? 'list' : 'table';
			setView((current) => ({
				...current,
				type,
				titleField: media.matches ? 'listing' : undefined,
				descriptionField: media.matches ? 'enquiry' : undefined,
				showMedia: false,
				fields: getVisibleFields(box, type),
			}));
		};
		updateLayout();
		media.addEventListener('change', updateLayout);
		return () => media.removeEventListener('change', updateLayout);
	}, [box]);

	useEffect(() => {
		let active = true;
		fetchEnquiryKPIs(box)
			.then((data) => {
				if (active) setResponseKPIs(data);
			})
			.catch(() => {
				if (active) setResponseKPIs({});
			});
		return () => {
			active = false;
		};
	}, [box, revision]);

	useEffect(() => {
		let active = true;
		setLoading(true);
		setError('');
		const timer = setTimeout(
			() => {
				fetchAllEnquiries(box, view)
					.then((data) => {
						if (!active) return;
						setResponses(data?.responses || []);
						setTotal(Number(data?.total) || 0);
						setLoadedBox(box);
					})
					.catch(() => {
						if (!active) return;
						setResponses([]);
						setTotal(0);
						setError(
							strings.load_error ||
								'Unable to load enquiries. Please try again.'
						);
					})
					.finally(() => {
						if (active) setLoading(false);
					});
			},
			view.search ? 250 : 0
		);
		return () => {
			active = false;
			clearTimeout(timer);
		};
	}, [box, view.page, view.perPage, view.search, revision]);

	useEffect(() => {
		let timer;
		const refreshWhenVisible = () => {
			if (document.visibilityState !== 'visible') return;
			clearTimeout(timer);
			timer = setTimeout(() => setRevision((value) => value + 1), 150);
		};

		window.addEventListener('focus', refreshWhenVisible);
		document.addEventListener('visibilitychange', refreshWhenVisible);
		return () => {
			clearTimeout(timer);
			window.removeEventListener('focus', refreshWhenVisible);
			document.removeEventListener('visibilitychange', refreshWhenVisible);
		};
	}, []);

	const kpiValue = (key) =>
		responseKPIs === null ? '\u2013' : responseKPIs[key] || 0;

	const enquiryStats = [
		{
			icon: <Inbox />,
			title:
				box === 'send'
					? strings.total_sent || 'Total Sent'
					: strings.total_enquiries || 'Total Enquiries',
			value: kpiValue('total'),
			type: 'total',
		},
		...(box === 'receive'
			? [
					{
						icon: <Envelope />,
						title: strings.new_messages || 'New Messages',
						value: kpiValue('unread'),
						type: 'new',
					},
				]
			: []),
		{
			icon: <Calendar />,
			title:
				box === 'send'
					? strings.sent_this_week || 'Sent This Week'
					: strings.this_week || 'This Week',
			value: kpiValue('this_week'),
			type: 'this-week',
		},
		...(box === 'receive'
			? [
					{
						icon: <Check />,
						title: strings.total_resolved || 'Total Resolved',
						value: kpiValue('read'),
						type: 'resolved',
					},
				]
			: []),
	];

	const handleRefresh = () => setRevision((value) => value + 1);
	const handleChangeView = (newView) => {
		setView((current) => ({
			...current,
			...newView,
			page:
				newView.search !== undefined && newView.search !== current.search
					? 1
					: newView.page || current.page,
		}));
	};
	const selectBox = (nextBox) => {
		if (nextBox === box) return;
		setLoading(true);
		setLoadedBox(null);
		setBox(nextBox);
		setResponses([]);
		setTotal(0);
		setResponseKPIs(null);
		setView((current) => ({
			...current,
			search: '',
			page: 1,
			fields: getVisibleFields(nextBox, current.type),
		}));
	};

	return (
		<EnquiriesComponentStyle className="directorist-enquiries-container">
			<div className="directorist-enquiries-header">
				<h1 className="directorist-enquiries-title">
					{strings.my_enquiries || 'My Enquiries'}
				</h1>
				<p className="directorist-enquiries-description">
					{box === 'send'
						? strings.sent_description || 'Forms you submitted on listings'
						: strings.enquiries_description ||
							'Track and manage all your incoming messages'}
				</p>
			</div>
			<div
				className="directorist-enquiries-tabs"
				role="tablist"
				aria-label={strings.enquiries || 'Enquiries'}
			>
				{['receive', 'send'].map((option) => (
					<button
						key={option}
						type="button"
						id={`directorist-enquiries-tab-${option}`}
						role="tab"
						aria-selected={box === option}
						aria-controls="directorist-enquiries-panel"
						className={box === option ? 'is-active' : ''}
						onClick={() => selectBox(option)}
					>
						{option === 'receive'
							? strings.receive || 'Received'
							: strings.send || 'Send'}
					</button>
				))}
			</div>
			<div
				id="directorist-enquiries-panel"
				role="tabpanel"
				aria-labelledby={`directorist-enquiries-tab-${box}`}
			>
				<div className="directorist-enquires-stats">
					{enquiryStats.map((item, index) => (
						<div
							className={`directorist-enquires-stats-item directorist-enquires-stats-item--${item.type}`}
							key={index}
						>
							<div className="directorist-enquires-stats-left">
								<h2>{item.value}</h2>
								<p>{item.title}</p>
							</div>
							<div className="directorist-enquires-stats-right">
								<span>{item.icon}</span>
							</div>
						</div>
					))}
				</div>

				<div className="directorist-enquiries-table" aria-busy={loading}>
					{error && (
						<div className="directorist-enquiries-error" role="alert">
							{error}{' '}
							<button type="button" onClick={handleRefresh}>
								{strings.retry || 'Retry'}
							</button>
						</div>
					)}
					{loading && loadedBox !== box && (
						<div className="directorist-enquiries-loading" role="status">
							<span
								className="directorist-enquiries-spinner"
								aria-hidden="true"
							/>
							<span>{strings.loading || 'Loading enquiries...'}</span>
						</div>
					)}
					{loadedBox === box && !error && (
						<Tables
							key={box}
							items={Array.isArray(responses) ? responses : []}
							box={box}
							view={view}
							onChangeView={handleChangeView}
							totalItems={total}
							handleTableRefresh={handleRefresh}
							strings={strings}
						/>
					)}
				</div>
			</div>
		</EnquiriesComponentStyle>
	);
};

export default EnquiriesComponent;

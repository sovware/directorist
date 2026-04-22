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
import { t } from '../utils/i18n';
import {
	fetchAllEnquiries,
	fetchEnquiryKPIs,
	refreshEnquiryData,
} from '../utils/enquiryUtils';
import { EnquiriesComponentStyle } from './style';
import Tables from './Table';

const EnquiriesComponent = ({ data = {} }) => {
	const [responseKPIs, setResponseKPIs] = useState({});
	const [responses, setResponses] = useState([]);

	//get response KPIs
	useEffect(() => {
		fetchEnquiryKPIs().then((data) => {
			setResponseKPIs(data);
		});
	}, []);

	useEffect(() => {
		fetchAllEnquiries().then((data) => {
			const items = Array.isArray(data) ? data : data?.responses || [];
			// Don't fetch answers here - let Table component handle lazy loading
			setResponses(items);
		});
	}, []);

	const enquiryStats = [
		{
			icon: <Inbox />,
			title: t('total_enquiries', 'Total Enquiries'),
			value: responseKPIs.total || 0,
			type: 'total',
		},
		{
			icon: <Envelope />,
			title: t('new_messages', 'New Messages'),
			value: responseKPIs.unread || 0,
			type: 'new',
		},
		{
			icon: <Calendar />,
			title: t('this_week', 'This Week'),
			value: responseKPIs.this_week || 0,
			type: 'this-week',
		},
		{
			icon: <Check />,
			title: t('total_resolved', 'Total Resolved'),
			value: responseKPIs.read || 0,
			type: 'resolved',
		},
	];

	const handleRefresh = async () => {
		try {
			const { responses, kpis } = await refreshEnquiryData();
			const items = Array.isArray(responses)
				? responses
				: responses?.responses || [];
			// Don't fetch answers here - let Table component handle lazy loading
			setResponses(items);
			setResponseKPIs(kpis);
		} catch (error) {
			console.error('Error refreshing data:', error);
		}
	};

	return (
		<EnquiriesComponentStyle className="directorist-enquiries-container">
			<div className="directorist-enquiries-header">
				<h1 className="directorist-enquiries-title">
					{t('my_enquiries', 'My Enquiries')}
				</h1>
				<p className="directorist-enquiries-description">
					{t(
						'dashboard_description',
						'Track and manage all your incoming messages'
					)}
				</p>
			</div>

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

			<div className="directorist-enquiries-table">
				<Tables
					items={Array.isArray(responses) ? responses : []}
					handleTableRefresh={handleRefresh}
				/>
			</div>
		</EnquiriesComponentStyle>
	);
};

export default EnquiriesComponent;

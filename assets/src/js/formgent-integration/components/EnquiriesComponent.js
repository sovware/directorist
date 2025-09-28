/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import Inbox from '../icons/Inbox';
import Envelope from '../icons/Envelope';
import Calendar from '../icons/Calendar';
import Check from '../icons/Check';
import { EnquiriesComponentStyle } from './style';
import Tables from './Table';
import {
	fetchEnquiryKPIs,
	fetchAllEnquiries,
	refreshEnquiryData,
} from '../utils/enquiryUtils';

const EnquiriesComponent = ({ data = {} }) => {
	const [responseKPIs, setResponseKPIs] = useState({});
	const [responses, setResponses] = useState([]);

	//get response KPIs
	useEffect(() => {
		fetchEnquiryKPIs().then((data) => {
			console.log('KPIs response:', data);
			setResponseKPIs(data);
		});
	}, []);

	useEffect(() => {
		fetchAllEnquiries().then((data) => {
			console.log('Responses response:', data);
			setResponses(data);
		});
	}, []);

	const enquiryStats = [
		{
			icon: <Inbox />,
			title: 'Total Enquiries',
			value: responseKPIs.total || 0,
			type: 'total',
		},
		{
			icon: <Envelope />,
			title: 'New Messages',
			value: responseKPIs.unread || 0,
			type: 'new',
		},
		{
			icon: <Calendar />,
			title: 'This Week',
			value: responseKPIs.this_week || 0,
			type: 'this-week',
		},
		{
			icon: <Check />,
			title: 'Total Resolved',
			value: responseKPIs.read || 0,
			type: 'resolved',
		},
	];

	const handleRefresh = async () => {
		try {
			const { responses, kpis } = await refreshEnquiryData();
			setResponses(responses);
			setResponseKPIs(kpis);
		} catch (error) {
			console.error('Error refreshing data:', error);
		}
	};

	return (
		<EnquiriesComponentStyle className="directorist-enquiries-container">
			<div className="directorist-enquiries-header">
				<h1 className="directorist-enquiries-title">My Enquiries</h1>
				<p className="directorist-enquiries-description">
					Track and manage all your incoming messages
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
					items={responses?.responses}
					handleTableRefresh={handleRefresh}
				/>
			</div>
		</EnquiriesComponentStyle>
	);
};

export default EnquiriesComponent;

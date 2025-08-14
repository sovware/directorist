

import { Fill } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Column } from '@wpmvc/components/build-types/gutenberg/table/types';
import { Table } from '@wpmvc/dashboard';
import React from "react";
import Card from '../../Card';
import Tab from '../../Tab';

const columns: Column[] = [
	{
		id: 'id',
		label: __( 'ID' ),
	},
	{
		id: 'title',
		label: __( 'Title' ),
	},
];

export default function Order() {
	const [activeTab, setActiveTab] = useState('general');
	return (
		<>
		<Fill name="wpmvc-header">
			<Tab
				className='tab-menu'
				tabs={[
					{
						name: 'general',
						title: 'General ino'
					},
					{
						name: 'feature',
						title: 'Feature Configuration'
					},
					{
						name: 'plan',
						title: 'Plan settings'
					}
				]}
				onActiveTab={setActiveTab} 
			/>
		</Fill>

		{
			activeTab === 'general' && <span>

				{/* Card with custom slot name */}
				<Card
					title="Custom Slot Card"
				>
					<p>This content will be replaced by Fill components.</p>
				</Card>
			</span>
		}
		{
			activeTab === 'feature' && <span>feature</span>
		}
		{
			activeTab === 'plan' && <span>plan</span>
		}
		
		<Table
			heading="Orders"
			path="/directorist/admin/orders"
			columns={ columns }
			create={
				{status: false}
			}
			edit={
				{status: false}
			}
			//@ts-ignore
			layoutType={ 'table' }
		/>
		</>
	);
}
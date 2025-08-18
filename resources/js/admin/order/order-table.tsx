/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { Column } from '@wpmvc/components/build-types/gutenberg/table/types';
import { Table } from '@wpmvc/dashboard';
import React from "react";

/**
 * Internal dependencies
 */

const columns: Column[] = [
	{
		id: 'id',
		label: __( 'ID', 'directorist' ),
	},
	{
		id: 'title',
		label: __( 'Title', 'directorist' ),
	},
];

export default function OrderTable() {
	return (
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
	);
}
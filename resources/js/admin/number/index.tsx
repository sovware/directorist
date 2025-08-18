/**
 * WordPress dependencies
 */
import { __experimentalNumberControl as NumberControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import React from "react";
import { NumberFieldType } from './types';

export default function Number( {
	field
}: NumberFieldType ) {
	return (
		<NumberControl
			{ ...field }
			help={ field.description }
			size="__unstable-large"
			step={ 1 }
		/>
	);
}

/**
 * WordPress dependencies
 */
import { __experimentalNumberControl as NumberControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { NumberFieldType } from './types';

export default function Number( props: NumberFieldType ): JSX.Element {
	return (
		<NumberControl
			{ ...props }
			help={ props.description }
			size="__unstable-large"
			step={ 1 }
		/>
	);
}

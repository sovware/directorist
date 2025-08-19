/**
 * WordPress dependencies
 */
import { CheckboxControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import styled from 'styled-components';

export const StyledCheckbox = styled( CheckboxControl )< {
	$disabled?: boolean;
} >`
	${ ( { $disabled } ) =>
		$disabled &&
		`
		pointer-events: none;
		opacity: 0.5;
	` }
`;

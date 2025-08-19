import { __experimentalVStack as VStack } from '@wordpress/components';
import styled from 'styled-components';

const StyleRadioField = styled( VStack )< {
	$disabled?: boolean;
	$variation?: 'normal' | 'boxed';
	$perRow?: number;
} >`
	${ ( { $variation, $perRow = 2 } ) =>
		'boxed' === $variation &&
		`
	display: flex;
	flex-direction: row !important;
	flex-wrap: wrap;
	gap: 20px !important;

	.components-radio-control__option-wrapper {	
		flex: 0 0 100%;
		//border: 1px solid var(--wpmvc-gray-600);
		//padding: 16px 16px;
		//border-radius: 4px;
		box-sizing: border-box;

		@media ( min-width: 768px ) {
			flex: 0 0 ${ `calc(${ 100 / $perRow }% - ${
				( ( $perRow - 1 ) * 20 ) / $perRow
			}px)` };
		}
	}

	.components-radio-control__option-description {
		margin-bottom: 0;
	}
	` }

	${ ( props ) =>
		props.$disabled &&
		`pointer-events: none;
		opacity: 0.5;
		` }
`;

export const RadioOption = styled.div< {
	$isBoxed?: boolean;
	$isRadioRight?: boolean;
} >`
	${ ( props ) =>
		props.$isBoxed &&
		`
		display: flex;
		flex-direction: ${ props.$isRadioRight ? 'row-reverse' : 'row' };
		justify-content: ${ props.$isRadioRight ? 'space-between' : 'flex-start' };
		align-items: center;
		padding: 20px 16px;
		border: 1px solid var(--wpmvc-gray-600);
		border-radius: 6px;
		cursor: pointer;
		transition: all 0.2s ease;
		` }
	.components-radio-control__label{
		display: flex;
		align-items: center;
		gap: 12px;
	}
`;

export default StyleRadioField;

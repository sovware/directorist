import { __experimentalInputControl as InputControl } from '@wordpress/components';
import styled from 'styled-components';

export const StyledInputField = styled(InputControl)<{
	$isDisabled: boolean;
}>`
	${(props) =>
		props.$isDisabled &&
		`
		pointer-events: none;
		opacity: 0.5;
	`}
`;

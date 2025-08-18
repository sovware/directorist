import { CheckboxProps } from './types';
import { StyledCheckbox } from './styles';

export default function Checkbox( props: CheckboxProps ): JSX.Element {
	return (
		<StyledCheckbox
			//@ts-ignore
			{ ...props }
			$disabled={ props.disabled }
			help={ props.description }
		/>
	);
}

import React from 'react';
import { StyledCheckbox } from './styles';
import { CheckboxProps } from './types';

export default function Checkbox({ field }: CheckboxProps) {
	return (
		<StyledCheckbox
			//@ts-ignore
			{...field}
			$disabled={field.disabled}
			help={field.description}
		/>
	);
}

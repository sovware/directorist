import { LabelPosition } from '@wordpress/components/build-types/input-control/types';
import { BaseField, BaseFieldProps } from '../../types/field';

export type TextFieldType = BaseField & {
	type: 'text';
	labelPosition?: LabelPosition;
};

export type TextFieldProps = BaseFieldProps & {
	field: TextFieldType;
};

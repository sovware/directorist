import {
	BaseField,
	BaseFieldProps,
	Option as BaseOption,
	FieldsType,
	Options,
} from '@shamim-ahmed/fields/build-types/types/field';

export type Option = BaseOption & {
	fields?: FieldsType;
};

type Field = BaseField & {
	type: 'radio';
	disabled?: boolean;
	variation?: 'normal';
	options: Options | ((attributes: Record<string, any>) => Options);
};

type BoxedRadioFieldType = BaseField & {
	type: 'radio';
	variation: 'boxed-right';
	perRow?: number;
	options:
		| Options<Option>
		| ((attributes: Record<string, any>) => Options<Option>);
};

export type RadioFieldType = Field | BoxedRadioFieldType;

export type RadioFieldProps = BaseFieldProps & {
	field: RadioFieldType;
	errors: object;
	setErrors: any;
};

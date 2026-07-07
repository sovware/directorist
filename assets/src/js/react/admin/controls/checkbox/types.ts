type FieldProps = {
	label: string;
	checked?: boolean;
	onChange: any;
	description?: string;
	disabled?: boolean;
	className?: string;
	required?: boolean;
};

export type CheckboxProps = {
	field: FieldProps;
};

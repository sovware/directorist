type Option = { 
	label: string; 
	value: string; 
	description?: string;
	icon?: React.ReactNode; // Support for icons
};

type FieldProps = {
	label: string;
	value: any;
	onChange: any;
	description?: string;
	disabled?: boolean;
	className?: string;
	required?: boolean;
	options: Array< Option >;
	variation?: 'normal' | 'boxed-left' | 'boxed-right';
	perRow?: number;
	showIcons?: boolean; // Option to show/hide icons
};

export type RadioProps = {
	field: FieldProps;
};

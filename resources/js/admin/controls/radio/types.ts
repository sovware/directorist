type Option = { 
	label: string; 
	value: string; 
	description?: string;
	icon?: React.ReactNode; // Support for icons
	after?: React.ReactNode; // Support for after content
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
	validation?: {
		[rule: string]: any | ((value: any, ...args: any[]) => string | null);
	};
	invalid_key?: string; // Key to track validation state
	help_text?: string; // Help text for validation
};

export type RadioProps = {
	field: FieldProps;
	attributes?: Record<string, any>; // For validation context
	setAttributes?: (updates: Record<string, any>) => void; // For validation state updates
};

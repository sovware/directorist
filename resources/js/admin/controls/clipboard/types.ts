type FieldProps = {
	text: string;
	label?: string;
	description?: string;
	timeout: any;
	className?: string;
	onCopied?: () => void;
};
export type ClipboardProps = {
	text: string;
	className?: string;
	timeout?: number;
	onCopied?: () => void;
	field: FieldProps;
};
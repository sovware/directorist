import { Fields } from '@wpmvc/fields';
import Text from './custom-field';
import Label from './label';
import Radio from './radio';

const components = {
	text: Text,
	n_radio: Radio,
	label: Label,
	// clipboard: Clipboard,
	// number: Number,
	// checkbox: Checkbox
	// You can override built-in types too, e.g. text: MyTextField
};

export default function Controls(props) {
	const { fields, attributes, setAttributes, errors, setErrors } = props;

	return (
		<Fields
			fields={fields}
			attributes={attributes}
			setAttributes={setAttributes}
			components={components}
			errors={errors}
			setErrors={setErrors}
			// validationTrigger={validationTrigger}
		/>
	);
}

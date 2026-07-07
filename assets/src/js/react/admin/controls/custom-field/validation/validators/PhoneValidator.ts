import { Validator } from '../Validator';

export class PhoneValidator extends Validator {
	validate(value: any): string | null {
		if (value && !/^[0-9]{10}$/.test(value)) {
			return 'Please enter a valid phone number';
		}
		return null;
	}
}

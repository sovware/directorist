import { Validator } from '../Validator';

export class EmailValidator extends Validator {
	validate(value: any): string | null {
		if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
			return 'Please enter a valid email address';
		}
		return null;
	}
}

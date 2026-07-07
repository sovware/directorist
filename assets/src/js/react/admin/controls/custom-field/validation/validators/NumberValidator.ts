import { Validator } from '../Validator';

export class NumberValidator extends Validator {
	validate(value: any): string | null {
		if (value && isNaN(Number(value))) {
			return 'Please enter a valid number';
		}
		return null;
	}
}

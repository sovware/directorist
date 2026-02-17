import { Validator } from '../Validator';

export class RequiredValidator extends Validator {
	validate(value: any): string | null {
		if (
			value === null ||
			value === undefined ||
			(typeof value === 'string' && value.trim() === '') ||
			(Array.isArray(value) && value.length === 0) ||
			(typeof value === 'object' &&
				!Array.isArray(value) &&
				!(value instanceof Date) &&
				Object.keys(value).length === 0)
		) {
			return 'This field is required';
		}
		return null;
	}
}

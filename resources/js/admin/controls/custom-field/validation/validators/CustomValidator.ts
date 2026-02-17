import { Validator } from '../Validator';

export class CustomValidator extends Validator {
	constructor(private fn: (value: any) => string | null) {
		super();
	}

	validate(value: any): string | null {
		return this.fn(value);
	}
}

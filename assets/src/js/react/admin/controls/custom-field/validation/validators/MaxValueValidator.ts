import { Validator } from '../Validator';

export class MaxValueValidator extends Validator {
	constructor(private max: number) {
		super();
	}

	validate(value: any): string | null {
		if (value && Number(value) > this.max) {
			return `Value must be no more than ${this.max}`;
		}
		return null;
	}
}

import { Validator } from '../Validator';

export class MinValueValidator extends Validator {
	constructor(private min: number) {
		super();
	}

	validate(value: any): string | null {
		if (value && Number(value) < this.min) {
			return `Value must be at least ${this.min}`;
		}
		return null;
	}
}

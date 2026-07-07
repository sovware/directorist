import { Validator } from '../Validator';

export class RegexValidator extends Validator {
	constructor(private pattern: string) {
		super();
	}

	validate(value: any): string | null {
		if (value && !new RegExp(this.pattern).test(value)) {
			return 'Value does not match required pattern';
		}
		return null;
	}
}

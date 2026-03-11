import { Validator } from "../Validator";

export class MinLengthValidator extends Validator {
  constructor(private min: number) {
    super();
  }

  validate(value: any): string | null {
    if (value && value.length < this.min) {
      return `Minimum length is ${this.min} characters`;
    }
    return null;
  }
}



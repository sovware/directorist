import { Validator } from "../Validator";

export class MaxLengthValidator extends Validator {
  constructor(private max: number) {
    super();
  }

  validate(value: any): string | null {
    if (value && value.length > this.max) {
      return `Maximum length is ${this.max} characters`;
    }
    return null;
  }
}



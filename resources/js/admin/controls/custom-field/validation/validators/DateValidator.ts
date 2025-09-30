import { Validator } from "../Validator";

export class DateValidator extends Validator {
  validate(value: any): string | null {
    if (value && isNaN(Date.parse(value))) {
      return "Please enter a valid date";
    }
    return null;
  }
}



import { Validator } from "../Validator";

export class URLValidator extends Validator {
  validate(value: any): string | null {
    if (value && !/^https?:\/\/.+/.test(value)) {
      return "Please enter a valid URL";
    }
    return null;
  }
}



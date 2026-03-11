import { Validator } from "./Validator";
import type { ValidationResult } from "./types";

export class FieldValidator {
  private validators: Validator[] = [];

  addValidator(validator: Validator) {
    this.validators.push(validator);
  }

  validate(value: any, attributes: Record<string, any> = {}): ValidationResult {
    const errors: string[] = [];

    for (const validator of this.validators) {
      const error = validator.validate(value, attributes);
      if (error) {
        errors.push(error);
      }
    }

    return {
      isValid: errors.length === 0,
      errors,
    };
  }
}



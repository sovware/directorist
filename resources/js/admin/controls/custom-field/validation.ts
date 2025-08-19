/**
 * Validation Module for Custom Fields
 * Contains built-in validators, custom validation support, and debounce functionality
 */

export type ValidatorFn = (value: any, ...args: any[]) => string | null;
export type AsyncValidatorFn = (
	value: any,
	attributes?: Record<string, any>,
	field?: FieldValidation
) => Promise<string | null>;

export interface FieldValidation {
	validation?: {
		[rule: string]:
			| any // numbers, strings, regex configs, etc.
			| ValidatorFn
			| AsyncValidatorFn
			| [string, string?]; // regex + optional message
	};
	[key: string]: any; // other field metadata
}

export const validators: Record<string, ValidatorFn> = {
	required: (value) => {
		if (!value || value.trim() === "") {
			return "This field is required";
		}
		return null;
	},

	email: (value) => {
		if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
			return "Please enter a valid email address";
		}
		return null;
	},

	min_length: (value, min: number) => {
		if (value && value.length < min) {
			return `Minimum length is ${min} characters`;
		}
		return null;
	},

	max_length: (value, max: number) => {
		if (value && value.length > max) {
			return `Maximum length is ${max} characters`;
		}
		return null;
	},

	pattern: (value, regex: string | RegExp) => {
		if (value && !new RegExp(regex).test(value)) {
			return "Value does not match required pattern";
		}
		return null;
	},

	number: (value) => {
		if (value && isNaN(Number(value))) {
			return "Please enter a valid number";
		}
		return null;
	},

	url: (value) => {
		if (value && !/^https?:\/\/.+/.test(value)) {
			return "Please enter a valid URL";
		}
		return null;
	},

	phone: (value) => {
		if (
			value &&
			!/^[\+]?[1-9][\d]{0,15}$/.test(value.replace(/[\s\-\(\)]/g, ""))
		) {
			return "Please enter a valid phone number";
		}
		return null;
	},

	min_value: (value, min: number) => {
		if (value && Number(value) < min) {
			return `Value must be at least ${min}`;
		}
		return null;
	},

	max_value: (value, max: number) => {
		if (value && Number(value) > max) {
			return `Value must be no more than ${max}`;
		}
		return null;
	},

	date: (value) => {
		if (value && isNaN(Date.parse(value))) {
			return "Please enter a valid date";
		}
		return null;
	},

	regex: (value, pattern: string | RegExp, message?: string) => {
		if (value && !new RegExp(pattern).test(value)) {
			return message || "Value does not match required pattern";
		}
		return null;
	},

	custom: (value, fn: ValidatorFn) => {
		if (typeof fn === "function") {
			return fn(value);
		}
		return null;
	},
};

// Debounce function for performance optimization
export const debounce = <T extends (...args: any[]) => void>(
	func: T,
	wait: number
) => {
	let timeout: ReturnType<typeof setTimeout>;
	return function executedFunction(...args: Parameters<T>) {
		const later = () => {
			clearTimeout(timeout);
			func(...args);
		};
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
	};
};

export interface ValidationResult {
	isValid: boolean;
	errors: string[];
}

// Main validation function
export const validateField = (
	value: any,
	field: FieldValidation,
	attributes: Record<string, any> = {}
): ValidationResult => {
	if (!field?.validation) return { isValid: true, errors: [] };

	const errors: string[] = [];

	Object.entries(field.validation).forEach(([rule, ruleValue]) => {
		if (typeof ruleValue === "function") {
			try {
				const error = (ruleValue as ValidatorFn)(value, attributes, field);
				if (error) {
					errors.push(error);
				}
			} catch (err) {
				console.error("Custom validation error:", err);
				errors.push("Validation error occurred");
			}
		} else if (validators[rule]) {
			const error = validators[rule](value, ruleValue);
			if (error) {
				errors.push(error);
			}
		} else if (rule === "regex" && Array.isArray(ruleValue)) {
			const [pattern, message] = ruleValue;
			const error = validators.regex(value, pattern, message);
			if (error) {
				errors.push(error);
			}
		}
	});

	return {
		isValid: errors.length === 0,
		errors,
	};
};

// Async validation support
export const validateFieldAsync = async (
	value: any,
	field: FieldValidation,
	attributes: Record<string, any> = {}
): Promise<ValidationResult> => {
	if (!field?.validation) return { isValid: true, errors: [] };

	const errors: string[] = [];
	const asyncValidations: Promise<string | null>[] = [];

	Object.entries(field.validation).forEach(([rule, ruleValue]) => {
		if (
			typeof ruleValue === "function" &&
			ruleValue.constructor.name === "AsyncFunction"
		) {
			asyncValidations.push(
				(ruleValue as AsyncValidatorFn)(value, attributes, field).catch(
					(err) => {
						console.error("Async validation error:", err);
						return "Validation error occurred";
					}
				)
			);
		} else if (typeof ruleValue === "function") {
			try {
				const error = (ruleValue as ValidatorFn)(value, attributes, field);
				if (error) {
					errors.push(error);
				}
			} catch (err) {
				console.error("Custom validation error:", err);
				errors.push("Validation error occurred");
			}
		} else if (validators[rule]) {
			const error = validators[rule](value, ruleValue);
			if (error) {
				errors.push(error);
			}
		}
	});

	if (asyncValidations.length > 0) {
		const asyncResults = await Promise.all(asyncValidations);
		asyncResults.forEach((error) => {
			if (error) {
				errors.push(error);
			}
		});
	}

	return {
		isValid: errors.length === 0,
		errors,
	};
};

export default validateField;
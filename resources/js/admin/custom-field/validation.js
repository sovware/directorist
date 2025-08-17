/**
 * Validation Module for Custom Fields
 * Contains built-in validators, custom validation support, and debounce functionality
 */

// Built-in validation functions
export const validators = {
	required: (value) => {
		if (!value || value.trim() === '') {
			return 'This field is required';
		}
		return null;
	},
	
	email: (value) => {
		if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
			return 'Please enter a valid email address';
		}
		return null;
	},
	
	minLength: (value, min) => {
		if (value && value.length < min) {
			return `Minimum length is ${min} characters`;
		}
		return null;
	},
	
	maxLength: (value, max) => {
		if (value && value.length > max) {
			return `Maximum length is ${max} characters`;
		}
		return null;
	},
	
	pattern: (value, regex) => {
		if (value && !new RegExp(regex).test(value)) {
			return 'Value does not match required pattern';
		}
		return null;
	},
	
	number: (value) => {
		if (value && isNaN(Number(value))) {
			return 'Please enter a valid number';
		}
		return null;
	},
	
	url: (value) => {
		if (value && !/^https?:\/\/.+/.test(value)) {
			return 'Please enter a valid URL';
		}
		return null;
	},
	
	phone: (value) => {
		if (value && !/^[\+]?[1-9][\d]{0,15}$/.test(value.replace(/[\s\-\(\)]/g, ''))) {
			return 'Please enter a valid phone number';
		}
		return null;
	},

	// Numeric range validation
	minValue: (value, min) => {
		if (value && Number(value) < min) {
			return `Value must be at least ${min}`;
		}
		return null;
	},

	maxValue: (value, max) => {
		if (value && Number(value) > max) {
			return `Value must be no more than ${max}`;
		}
		return null;
	},

	// Date validation
	date: (value) => {
		if (value && isNaN(Date.parse(value))) {
			return 'Please enter a valid date';
		}
		return null;
	},

	// File size validation (for file inputs)
	maxFileSize: (value, maxSizeMB) => {
		if (value && value.size && value.size > maxSizeMB * 1024 * 1024) {
			return `File size must be less than ${maxSizeMB}MB`;
		}
		return null;
	},

	// Custom regex validation
	regex: (value, pattern, message) => {
		if (value && !new RegExp(pattern).test(value)) {
			return message || 'Value does not match required pattern';
		}
		return null;
	}
};

// Debounce function for performance optimization
export const debounce = (func, wait) => {
	let timeout;
	return function executedFunction(...args) {
		const later = () => {
			clearTimeout(timeout);
			func(...args);
		};
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
	};
};

// Main validation function
export const validateField = (value, field, attributes = {}) => {
	if (!field?.validation) return { isValid: true, errors: [] };

	const errors = [];

	// Run each validation rule
	Object.entries(field.validation).forEach(([rule, ruleValue]) => {
		// Check for custom validation function
		if (typeof ruleValue === 'function') {
			try {
				const error = ruleValue(value, attributes, field);
				if (error) {
					errors.push(error);
				}
			} catch (err) {
				console.error('Custom validation error:', err);
				errors.push('Validation error occurred');
			}
		}
		// Check for built-in validators
		else if (validators[rule]) {
			const error = validators[rule](value, ruleValue);
			if (error) {
				errors.push(error);
			}
		}
		// Check for regex validation with custom message
		else if (rule === 'regex' && Array.isArray(ruleValue)) {
			const [pattern, message] = ruleValue;
			const error = validators.regex(value, pattern, message);
			if (error) {
				errors.push(error);
			}
		}
	});

	return {
		isValid: errors.length === 0,
		errors
	};
};

// Async validation support
export const validateFieldAsync = async (value, field, attributes = {}) => {
	if (!field?.validation) return { isValid: true, errors: [] };

	const errors = [];
	const asyncValidations = [];

	// Run each validation rule
	Object.entries(field.validation).forEach(([rule, ruleValue]) => {
		// Check for async custom validation function
		if (typeof ruleValue === 'function' && ruleValue.constructor.name === 'AsyncFunction') {
			asyncValidations.push(
				ruleValue(value, attributes, field).catch(err => {
					console.error('Async validation error:', err);
					return 'Validation error occurred';
				})
			);
		}
		// Check for custom validation function
		else if (typeof ruleValue === 'function') {
			try {
				const error = ruleValue(value, attributes, field);
				if (error) {
					errors.push(error);
				}
			} catch (err) {
				console.error('Custom validation error:', err);
				errors.push('Validation error occurred');
			}
		}
		// Check for built-in validators
		else if (validators[rule]) {
			const error = validators[rule](value, ruleValue);
			if (error) {
				errors.push(error);
			}
		}
	});

	// Wait for async validations to complete
	if (asyncValidations.length > 0) {
		const asyncResults = await Promise.all(asyncValidations);
		asyncResults.forEach(error => {
			if (error) {
				errors.push(error);
			}
		});
	}

	return {
		isValid: errors.length === 0,
		errors
	};
};

// Export default validation function
export default validateField;

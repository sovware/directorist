/**
 * WordPress dependencies
 */
import { __experimentalInputControl as InputControl } from '@wordpress/components';
import { useCallback, useRef, useState } from '@wordpress/element';

/**
 * External dependencies
 */
import { has } from 'lodash';
import styled, { css } from 'styled-components';

/**
 * Internal dependencies
 */
import { debounce, validateField } from './validation';

const StyledInput = styled(InputControl)`
	${(props) => {
		if (props.isInvalid) {
			return css`
				.components-input-control__backdrop {
					border-color: #d63638 !important;
				}
			`;
		}
	}}
`;

const ValidationError = styled.div`
	color: #d63638;
	font-size: 12px;
	margin-top: 4px;
`;

export default function TextControl(props) {
	const { attrKey, field, attributes, setAttributes } = props;
	const [validationErrors, setValidationErrors] = useState([]);
	const [isValidating, setIsValidating] = useState(false);
	const debounceRef = useRef(null);

	const isInvalid = field?.invalid_key
		? attributes[field.invalid_key]
		: validationErrors.length > 0;

	// Create debounced validation function
	const debouncedValidation = useCallback(
		debounce((value) => {
			performValidation(value);
		}, 300), // 300ms delay
		[]
	);

	// Perform validation using the validation module
	const performValidation = (value) => {
		if (!field?.validation) return;

		setIsValidating(true);
		
		// Use the validation module
		const result = validateField(value, field, attributes);
		
		setValidationErrors(result.errors);
		setIsValidating(false);

		// Update invalid state if field has invalid_key
		if (field.invalid_key) {
			setAttributes({ [field.invalid_key]: !result.isValid });
		}
	};

	// Handle field change with debounced validation
	const handleChange = (value) => {
		// Update the attribute value
		setAttributes({ [attrKey]: value });
		
		// Clear previous debounce
		if (debounceRef.current) {
			clearTimeout(debounceRef.current);
		}

		// Run debounced validation if enabled
		if (field?.validation) {
			debouncedValidation(value);
		}

		// Call custom onChange if provided
		if (has(field, 'onChange')) {
			field.onChange({
				value,
				...props,
			});
		}
	};

	return (
		<div>
			<StyledInput
				isInvalid={isInvalid}
				label={field.label}
				value={attributes[attrKey]}
				help={field.description}
				size="__unstable-large"
				type={field?.inputType}
				onClick={(event) => event.stopPropagation()}
				onChange={handleChange}
				onBlur={() => {
					// Clear debounce and validate immediately on blur
					if (debounceRef.current) {
						clearTimeout(debounceRef.current);
					}
					
					// Validate on blur if not already validating
					if (field?.validation && !isValidating) {
						performValidation(attributes[attrKey]);
					}
				}}
			/>
			
			{/* Show validation errors */}
			{validationErrors.length > 0 && (
				validationErrors.map((error, index) => (
					<ValidationError key={index}>{error}</ValidationError>
				))
			)}
		</div>
	);
}
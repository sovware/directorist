/**
 * WordPress dependencies
 */
import { __experimentalInputControl as InputControl } from '@wordpress/components';
import { useCallback, useRef, useState } from '@wordpress/element';

/**
 * External dependencies
 */
import styled, { css } from 'styled-components';

/**
 * Internal dependencies
 */
import { useEffect } from 'react';
import { debounce, validateField } from './validation';

const StyledInput = styled(InputControl)`
	${(props) => {
		console.log(props);
		
		if (props.isInvalid) {
			return css`
				.components-input-control__backdrop {
					border-color: var(--directorist-color-danger) !important;
					box-shadow: 0 0 0 0.5px var(--directorist-color-danger) !important;
				}
			`;
		}
	}}
`;

const ValidationError = styled.div`
	color: var(--directorist-color-danger);
	font-size: 12px;
	margin-top: 4px;
`;

export default function TextControl(props) {
	const { attrKey, field, attributes, setAttributes } = props;
	const { validationErrors } = attributes;
	const [isValidating, setIsValidating] = useState(false);
	const debounceRef = useRef(null);

	
	

	const isInvalid = validationErrors[attrKey]?.length === 0;
	console.log('error', isInvalid, validationErrors, validationErrors[attrKey]?.length, props);

	// Create debounced validation function
	const debouncedValidation = useCallback(
		debounce((value) => {
			performValidation(value);
		}, 300), // 300ms delay
		[]
	);

	useEffect(()=>{
		if(attributes?.should_validate){
			performValidation(attributes[attrKey]);
		}
	},[attributes?.should_validate])

	// Perform validation using the validation module
	const performValidation = (value) => {
		if (!field?.validation) return;

		setIsValidating(true);
		
		// Use the validation module
		const result = validateField(value, field, attributes);
		// console.log(result);
		
		setAttributes({
			validationErrors: {
			  ...validationErrors,
			  [attrKey]: result.errors,
			},
		  });
		// setValidationErrors(result.errors);
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
	};

	return (
		<div>
			<StyledInput
				isInvalid={validationErrors[attrKey]?.length > 0}
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
			{validationErrors[attrKey]?.length > 0 && (
				validationErrors[attrKey].map((error, index) => (
					<ValidationError key={index}>{error}</ValidationError>
				))
			)}
		</div>
	);
}
/**
 * WordPress dependencies
 */
import { __experimentalInputControl as InputControl } from '@wordpress/components';
import { debounce } from '@wordpress/compose';
import { useCallback, useRef } from '@wordpress/element';

/**
 * External dependencies
 */
import React from 'react';
import styled, { css } from 'styled-components';

/**
 * Internal dependencies
 */
import { doAction } from '@wordpress/hooks';
interface StyledProps {
	$isInvalid?: boolean;
	$isDisabled?: boolean;
}
const StyledInput = styled(InputControl)<StyledProps>`
	${(props) => {
		if (props.$isInvalid) {
			return css`
				.components-input-control__backdrop {
					border-color: var(--directorist-color-danger) !important;
					box-shadow: 0 0 0 0.5px var(--directorist-color-danger) !important;
				}
			`;
		}
	}}

	${(props) =>
		props.$isDisabled &&
		`
		pointer-events: none;
		opacity: 0.5;
	`}
`;

const ValidationError = styled.div`
	color: var(--directorist-color-danger);
	font-size: 12px;
	margin-top: 4px;
`;

export default function TextControl(props) {
	const { attrKey, field, attributes, setAttributes, errors, setErrors } =
		props;
	const debounceRef = useRef(null);
	const fieldErrors = errors?.[attrKey];

	// Create debounced validation function
	const debouncedValidation = useCallback(
		debounce((value) => {
			doAction('wpmvc-field-on-change', {
				value: value,
				field,
				fieldKey: attrKey,
				attributes,
				errors,
				setErrors,
			});
		}, 100), // 100ms delay
		[]
	);

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
				{...field}
				value={attributes[attrKey]}
				$isInvalid={fieldErrors?.length > 0}
				$isDisabled={field.disabled}
				help={field?.description}
				size="default"
				onChange={handleChange}
				onBlur={() => {
					// Clear debounce and validate immediately on blur
					if (debounceRef.current) {
						clearTimeout(debounceRef.current);
					}
					// Validate on blur if not already validating
					if (field?.validation) {
						// performValidation(attributes[attrKey]);
						doAction('wpmvc-field-on-blur', {
							value: attributes[attrKey],
							field,
							fieldKey: attrKey,
							attributes,
							errors,
							setErrors,
						});
					}
				}}
			/>

			{/* Show validation errors */}
			{fieldErrors?.length > 0 &&
				fieldErrors.map((error, index) => (
					<ValidationError key={index}>{error}</ValidationError>
				))}
		</div>
	);
}

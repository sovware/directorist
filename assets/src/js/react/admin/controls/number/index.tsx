/**
 * WordPress dependencies
 */
import { __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { debounce } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { useCallback, useEffect, useRef, useState } from '@wordpress/element';
import styled, { css } from 'styled-components';
import validateField from '../custom-field/validation';
import { NumberFieldType } from './types';

interface StyledProps {
	$isInvalid?: boolean;
	$isDisabled?: boolean;
}
const StyledNumber = styled(NumberControl)<StyledProps>`
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

export default function Number(props: NumberFieldType) {
	const { attrKey, field, attributes, setAttributes } = props;
	const { validationErrors } = attributes;
	const [isValidating, setIsValidating] = useState(false);
	const debounceRef = useRef(null);
	const fieldErrors = validationErrors[attrKey];

	// Create debounced validation function
	const debouncedValidation = useCallback(
		debounce((value) => {
			performValidation(value);
		}, 300), // 300ms delay
		[]
	);

	useEffect(() => {
		if (attributes?.should_validate) {
			performValidation(attributes[attrKey]);
		}
	}, [attributes?.should_validate]);

	// Perform validation using the validation module
	const performValidation = (value) => {
		if (!field?.validation) return;
		setIsValidating(true);

		// Use the validation module
		const result = validateField(value, field, attributes);

		setAttributes({
			validationErrors: {
				...validationErrors,
				[attrKey]: result.errors,
			},
		});
		setIsValidating(false);
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
		<>
			<StyledNumber
				{...field}
				$isInvalid={fieldErrors?.length > 0}
				$isDisabled={field.disabled}
				value={attributes[attrKey]}
				help={field.description}
				size="__unstable-large"
				step={1}
				onChange={handleChange}
				onBlur={() => {
					// Clear debounce and validate immediately on blur
					if (debounceRef.current) {
						clearTimeout(debounceRef.current);
					}
					performValidation(attributes[attrKey]);
				}}
				aria-invalid={fieldErrors?.length > 0}
			/>
			{/* Show validation errors */}
			{fieldErrors?.length > 0 &&
				fieldErrors.map((error, index) => (
					<ValidationError key={index}>{error}</ValidationError>
				))}
		</>
	);
}

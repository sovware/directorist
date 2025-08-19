/**
 * WordPress dependencies
 */
import React, { useCallback, useEffect, useState } from 'react';

/**
 * External dependencies
 */
import { size } from 'lodash';

/**
 * Internal dependencies
 */
import { validateField, ValidationResult } from '../custom-field/validation';
import StyleRadioField, { RadioOption } from './styles';
import { RadioProps } from './types';

/**
 * Generate a unique description ID for each radio option
 */
const generateOptionDescriptionId = (groupId: string, index: number) =>
	`${groupId}-${index}-option-description`;

/**
 * Generate a unique ID for each radio option
 */
const generateOptionId = (groupId: string, index: number) =>
	`${groupId}-${index}`;

export default function Radio({ field, attributes = {}, setAttributes }: RadioProps) {
	const {
		label,
		options,
		value,
		onChange,
		disabled,
		variation = 'normal',
		perRow,
		validation,
		invalid_key,
		help_text,
	} = field;

	const id = 'radio-id';

	// Validation state
	const [validationErrors, setValidationErrors] = useState<string[]>([]);
	const [isValidating, setIsValidating] = useState(false);

	// Boxed variation helpers
	const isBoxed = variation.startsWith('boxed-');
	const isRadioRight = variation === 'boxed-right';

	// Validation helpers
	const isInvalid = invalid_key ? attributes[invalid_key] : validationErrors.length > 0;

	// Perform validation
	const performValidation = useCallback((currentValue: any) => {
		if (!validation) return;

		setIsValidating(true);
		const result: ValidationResult = validateField(currentValue, { validation }, attributes);
		setValidationErrors(result.errors);
		setIsValidating(false);

		// Update validation state in parent component if invalid_key is provided
		if (invalid_key && setAttributes) {
			setAttributes({ [invalid_key]: !result.isValid });
		}
	}, [validation, attributes, invalid_key, setAttributes]);

	// Validate on value change
	useEffect(() => {
		if (validation) {
			performValidation(value);
		}
	}, [value, validation, performValidation]);

	// Handle option change with validation
	const handleOptionChange = useCallback((optionValue: any) => {
		onChange(optionValue);
		
		// Perform validation if validation rules exist
		if (validation) {
			performValidation(optionValue);
		}
	}, [onChange, validation, performValidation]);

	return (
		<div
			id={id}
			className="components-radio-control"
			style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}
		>
			{label && <label>{label}</label>}

			<StyleRadioField
				spacing={3}
				className="components-radio-control__group-wrapper"
				$variation={isBoxed ? 'boxed' : 'normal'}
				$disabled={disabled}
				$perRow={perRow || size(options)}
			>
				{options.map((option, index) => {
					const optionId = generateOptionId(id, index);
					const descriptionId = generateOptionDescriptionId(id, index);

					// Show "after" element only if boxed + selected
					const hasAfter = isBoxed && option?.after && value === option.value;

					return (
						<div
							key={optionId}
							className="components-radio-control__option-wrapper"
						>
							<RadioOption
								$isBoxed={isBoxed}
								$isRadioRight={isRadioRight}
								className="components-radio-control__option"
								style={{ 
									marginBottom: hasAfter ? 12 : 0,
									...(isInvalid && isBoxed && {
										borderColor: 'var(--directorist-color-danger)',
										boxShadow: '0 0 0 1px var(--directorist-color-danger)',
									}),
								}}
								onClick={isBoxed ? () => handleOptionChange(option.value) : undefined}
							>
								<input
									id={optionId}
									className="components-radio-control__input"
									type="radio"
									name={id}
									value={option.value}
									checked={option.value === value}
									onChange={() => handleOptionChange(option.value)}
									aria-describedby={option.description ? descriptionId : undefined}
									style={{ margin: 0 }}
									aria-invalid={isInvalid}
								/>

								<div className="components-radio-control__label">
									{option.icon}
									<label
										htmlFor={optionId}
										className="components-radio-control__label"
										style={{ cursor: isBoxed ? 'pointer' : undefined }}
									>
										{option.label}
									</label>
								</div>

								{option.description && (
									<p
										id={descriptionId}
										className="components-radio-control__option-description"
										style={{
											margin: '4px 0 0 0',
											fontSize: '12px',
											color: '#666',
											...(isBoxed && isRadioRight && { textAlign: 'right' }),
										}}
									>
										{option.description}
									</p>
								)}
							</RadioOption>

							{/* @ts-ignore */}
							{hasAfter && option.after}
						</div>
					);
				})}
			</StyleRadioField>

			{/* Validation errors */}
			{validationErrors.length > 0 && (
				<div style={{ 
					color: 'var(--directorist-color-danger)', 
					fontSize: '12px', 
					marginTop: '4px' 
				}}>
					{validationErrors.map((error, index) => (
						<div key={index}>{error}</div>
					))}
				</div>
			)}

			{/* Help text */}
			{help_text && (
				<p style={{ 
					fontSize: '12px', 
					color: '#666', 
					marginTop: '4px',
					fontStyle: 'italic'
				}}>
					{help_text}
				</p>
			)}
		</div>
	);
}
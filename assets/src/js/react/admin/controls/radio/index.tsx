/**
 * WordPress dependencies
 */
import { useInstanceId } from '@wordpress/compose';
import React, { useCallback, useState } from 'react';

/**
 * External dependencies
 */
import { size } from 'lodash';

/**
 * Internal dependencies
 */
// import { updateAttributes } from '@shamim-ahmed/fields';
import { doAction } from '@wordpress/hooks';
import styled from 'styled-components';
import { validateField } from '../custom-field/validation';
import StyleRadioField, { RadioOption } from './styles';
import { RadioFieldProps } from './types';

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

const ValidationError = styled.div`
	color: var(--directorist-color-danger);
	font-size: 12px;
	margin-top: 4px;
`;

export default function Radio(props: RadioFieldProps) {
	const { attrKey, field, attributes, setAttributes, errors, setErrors } =
		props;
	const {
		label,
		options,
		// value,
		onChange,
		disabled,
		variation = 'normal',
		perRow,
		validation,
		help_text,
	} = field;

	const id = useInstanceId(Radio, 'wpmvc-radio');
	const value = attributes[attrKey];
	const [isValidating, setIsValidating] = useState(false);
	const fieldErrors = errors?.[attrKey];

	// Boxed variation helpers
	const isBoxed = variation.startsWith('boxed-');
	const isRadioRight = variation === 'boxed-right';

	// Perform validation
	const performValidation = (currentValue) => {
		if (!field?.validation) return;
		setIsValidating(true);

		// Use the validation module
		const result = validateField(currentValue, field, attributes);
		const currentValidationErrors = attributes.validationErrors || {};
		const updatedValidationErrors = {
			...currentValidationErrors,
			[attrKey]: result.errors,
		};
		setErrors(updatedValidationErrors);

		setIsValidating(false);
	};

	// Handle option change with validation
	const handleOptionChange = useCallback((optionValue: any) => {
		// onChange(optionValue);
		setAttributes({
			[attrKey]: optionValue,
		});

		// Perform validation if validation rules exist
		if (validation) {
			// performValidation(optionValue);
			doAction('wpmvc-field-on-change', {
				value: optionValue,
				field,
				fieldKey: attrKey,
				attributes,
				errors,
				setErrors,
			});
		}
	}, []);

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
					const descriptionId = generateOptionDescriptionId(
						id,
						index
					);

					// Show "after" element only if boxed + selected
					const hasAfter =
						isBoxed && option?.after && value === option.value;

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
									...(fieldErrors?.length > 0 &&
										isBoxed && {
											borderColor:
												'var(--directorist-color-danger)',
											boxShadow:
												'0 0 0 1px var(--directorist-color-danger)',
										}),
								}}
								onClick={
									isBoxed
										? () => handleOptionChange(option.value)
										: undefined
								}
							>
								<input
									id={optionId}
									className="components-radio-control__input"
									type="radio"
									name={id}
									value={option.value}
									checked={option.value === value}
									onChange={() =>
										handleOptionChange(option.value)
									}
									aria-describedby={
										option.description
											? descriptionId
											: undefined
									}
									style={{ margin: 0 }}
									aria-invalid={fieldErrors?.length > 0}
								/>

								<div className="components-radio-control__label">
									{option.icon}
									<label
										htmlFor={optionId}
										className="components-radio-control__label"
										style={{
											cursor: isBoxed
												? 'pointer'
												: undefined,
										}}
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
											...(isBoxed &&
												isRadioRight && {
													textAlign: 'right',
												}),
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

			{/* Show validation errors */}
			{fieldErrors?.length > 0 &&
				fieldErrors.map((error, index) => (
					<ValidationError key={index}>{error}</ValidationError>
				))}

			{/* Help text */}
			{help_text && (
				<p
					style={{
						fontSize: '12px',
						color: '#666',
						marginTop: '4px',
						fontStyle: 'italic',
					}}
				>
					{help_text}
				</p>
			)}
		</div>
	);
}

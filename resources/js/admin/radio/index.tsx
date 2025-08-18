/**
 * WordPress dependencies
 */
import React from 'react';

/**
 * External dependencies
 */
import { size } from 'lodash';

/**
 * Internal dependencies
 */
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

export default function Radio({ field }: RadioProps) {
	const {
		label,
		options,
		value,
		onChange,
		disabled,
		variation = 'normal',
		perRow,
	} = field;

	const id = 'radio-id';

	// Boxed variation helpers
	const isBoxed = variation.startsWith('boxed-');
	const isRadioRight = variation === 'boxed-right';

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
								style={{ marginBottom: hasAfter ? 12 : 0 }}
								onClick={isBoxed ? () => onChange(option.value) : undefined}
							>
								<input
									id={optionId}
									className="components-radio-control__input"
									type="radio"
									name={id}
									value={option.value}
									checked={option.value === value}
									onChange={() => onChange(option.value)}
									aria-describedby={option.description ? descriptionId : undefined}
									style={{ margin: 0 }}
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
		</div>
	);
}
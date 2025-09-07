/**
 * WordPress dependencies
 */
import { Fill } from '@wordpress/components';
import { useCallback, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { FieldsType } from '@wpmvc/fields/build-types/types/field';
import React from 'react';

/**
 * Internal dependencies
 */
import { useAttributes } from '@wpmvc/dashboard';
import validateField from '../../controls/custom-field/validation.ts';
import ElementorIcon from '../../icons/elementorIcon.tsx';
import Tab from '../../Tab.tsx';
import Feature from './feature.tsx';
import General from './general.tsx';
import Plan from './plan.tsx';

const editOrderInitialValues = {
	plan_name: '',
	description: 'active',
	directory_type: '',
	listing_count: 0,
	is_listing_unlimited: false,
	featured_listing_count: 1,
	is_featured_listing_unlimited: false,
	plan_visibility: 'hidden',
	// should_validate: false,
	// validationErrors: {}
};

interface ExtendedFieldType extends FieldsType {
	[key: string]: any; // Allow additional properties
}

const basicFields: ExtendedFieldType = {
	// custom: {
	//   type: 'custom',
	//   label: 'Custom Field',
	//   description: 'This is a custom field.',
	//   // Validation props that the custom field component will use
	//   validation: {
	//     required: true,
	//     min_length: 3,
	//     max_length: 50,
	//   },
	//   invalid_key: 'custom_invalid',
	//   onChange: (data: any) => {
	//     console.log('Custom field changed:', data.value);
	//   }
	// },
	plan_name: {
		type: 'text',
		label: __('What’s the name of your plan?', 'directorist'),
		description: __(
			'This is the name of your plan that will be displayed to the users.',
			'directorist'
		),
		validation: {
			required: true,
			min_length: 3,
			max_length: 50,
			callback: (value, attributes) => {
				console.log(value, attributes);
				return null;
			},
		},
	},
	// description: {
	//   type: "text",
	//   label: __("Short Description", "directorist")
	// },
	directory_type: {
		type: 'n_radio',
		label: __('Select directory type', 'directorist'),
		variation: 'boxed-right',
		validation: {
			required: true,
		},
		options: [
			{
				label: 'Jobs',
				value: 'jobs',
				icon: <ElementorIcon />,
				renderRadio: ([option, props]) => <>Hello</>,
			},
			{
				label: 'Restaurant',
				value: 'restaurant',
				icon: <ElementorIcon />,
				renderRadio: ([option, props]) => <>Hello</>,
			},
		],
	},
	// listing_count: {
	//   type: "number",
	//   label: __("How many listings for this package?", "directorist"),
	//   // min: 1,
	//   // max: 100,
	//   step: 1,
	//   defaultValue: 10,
	//   // validation: {
	//   //   required: true,
	//   //   min_value: 2,
	//   //   max_value: 100,
	//   // },
	// },
	// listing_unlimited: {
	//   type: "checkbox",
	//   label: __("Or Mark as Unlimited", "directorist"),
	// },
	// featured_listing_count: {
	//   type: "number",
	//   label: __("Number of featured listing.", "directorist"),
	//   min: 1,
	//   max: 100,
	//   step: 1,
	//   defaultValue: 10,
	// },
	// featured_listing_unlimited: {
	//   type: "checkbox",
	//   label: __("Or Mark as Unlimited", "directorist"),
	// }
};
const planFields: FieldsType = {
	plan_visibility: {
		type: 'select',
		label: __('Visibility', 'directorist'),
		options: [
			{
				label: __('Hidden from all plan', 'directorist'),
				value: 'hidden',
			},
			{
				label: __('Visible to all plan', 'directorist'),
				value: 'visible',
			},
		],
		isMulti: false,
	},
	clipboard: {
		type: 'text',
		label: __('Embed plan', 'directorist'),
		description: __(
			'Easily embed this plan anywhere with this shortcode',
			'directorist'
		),
	},
};

export default function Edit() {
	const [activeTab, setActiveTab] = useState('general');
	const [attributes, setAttributes] = useAttributes({
		...editOrderInitialValues,
	});
	const [errors, setErrors] = useAttributes({});
	const [isSubmitting, setIsSubmitting] = useState(false);

	// Handle form submission
	const handleSubmit = useCallback(
		async (e: React.FormEvent) => {
			e.preventDefault();

			const allFields = { ...basicFields, ...planFields };
			const updatedErrors = {};
			Object.keys(allFields).forEach((fieldKey) => {
				const field = allFields[fieldKey];
				const value = attributes[fieldKey];
				const errorsResult = validateField(value, field, attributes);
				updatedErrors[fieldKey] = errorsResult.errors;
			});

			setErrors(updatedErrors);
		},
		[attributes]
	);

	return (
		<form onSubmit={handleSubmit}>
			<Fill name="wpmvc-header">
				<Tab
					className="tab-menu"
					tabs={[
						{
							name: 'general',
							title: 'General Info',
						},
						{
							name: 'feature',
							title: 'Feature Configuration',
						},
						{
							name: 'plan',
							title: 'Plan Settings',
						},
					]}
					onActiveTab={setActiveTab}
				/>
			</Fill>

			{activeTab === 'general' && (
				<General
					attributes={attributes}
					setAttributes={setAttributes}
					cards={{
						basicFields,
						planFields,
					}}
					errors={errors}
					setErrors={setErrors}
				/>
			)}
			{activeTab === 'feature' && (
				<Feature
					attributes={attributes}
					setAttributes={setAttributes}
				/>
			)}
			{activeTab === 'plan' && (
				<Plan attributes={attributes} setAttributes={setAttributes} />
			)}

			<button
				type="submit"
				disabled={isSubmitting}
				style={{
					padding: '10px 20px',
					backgroundColor: isSubmitting ? '#ccc' : '#007cba',
					color: 'white',
					border: 'none',
					borderRadius: '4px',
					cursor: isSubmitting ? 'not-allowed' : 'pointer',
					fontSize: '16px',
				}}
			>
				{isSubmitting ? 'Submitting...' : 'Submit'}
			</button>
		</form>
	);
}

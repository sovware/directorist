import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';

import blockAttributesMap from './attributes.json';
import getLogo from '../logo';
import './editor.scss';

let transformAttributesMap = {};

for ( const [key, value] of Object.entries( blockAttributesMap ) ) {
	transformAttributesMap[key] = {
		type: value.type,
		shortcode: ({named}) => {
			if (typeof named[key] === 'undefined' ) {
				return value.default;
			}

			if (value.type === 'string') {
				return String(named[key]);
			}

			if (value.type === 'number') {
				return Number(named[key]);
			}

			if (value.type === 'boolen') {
				return Boolean(named[key]);
			}
		}
	}
}

registerBlockType( 'directorist/search-listing', {
	apiVersion: 2,

	title: __( 'Search Form', 'directorist' ),

	description: __( 'Create directory listing search form.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_search_listing',
				attributes: transformAttributesMap
			},
		]
	},

	attributes: blockAttributesMap,

	edit( { attributes, setAttributes } ) {
		let {
			show_title_subtitle,
			search_bar_title,
			search_bar_sub_title,
			text_field,
			category_field,
			location_field,
			search_button_text,
			more_filters_button,
			more_filters_text,
			price_min_max_field,
			price_range_field,
			rating_field,
			tag_field,
			open_now_field,
			custom_fields,
			website_field,
			email_field,
			phone_field,
			fax,
			address_field,
			zip_code_field,
			reset_filters_button,
			apply_filters_button,
			reset_filters_text,
			apply_filters_text,
			more_filters_display,
			logged_in_user_only
		} = attributes;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Settings', 'directorist' ) } initialOpen={ true }>
						<ToggleControl
							label={ __( 'Show Title & Subtitle?', 'directorist' ) }
							checked={ show_title_subtitle }
							onChange={ newState => setAttributes( { show_title_subtitle: newState } ) }
						/>
						{ show_title_subtitle ? <TextControl
							label={ __( 'Search Form Title', 'directorist' ) }
							type='text'
							value={ search_bar_title }
							onChange={ newState => setAttributes( { search_bar_title: newState } ) }
						/> : '' }
						{ show_title_subtitle ? <TextControl
							label={ __( 'Search Form Subtitle', 'directorist' ) }
							type='text'
							value={ search_bar_sub_title }
							onChange={ newState => setAttributes( { search_bar_sub_title: newState } ) }
						/> : '' }
						<ToggleControl
							label={ __( 'Show Text Field?', 'directorist' ) }
							checked={ text_field }
							onChange={ newState => setAttributes( { text_field: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Category Field?', 'directorist' ) }
							checked={ category_field }
							onChange={ newState => setAttributes( { category_field: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Location Field?', 'directorist' ) }
							checked={ location_field }
							onChange={ newState => setAttributes( { location_field: newState } ) }
						/>
						<TextControl
							label={ __( 'Search Button Label', 'directorist' ) }
							type='text'
							value={ search_button_text }
							onChange={ newState => setAttributes( { search_button_text: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show More Filters Button?', 'directorist' ) }
							checked={ more_filters_button }
							onChange={ newState => setAttributes( { more_filters_button: newState } ) }
						/>
						{ more_filters_button ? <TextControl
							label={ __( 'More Filters Button Label', 'directorist' ) }
							type='text'
							value={ more_filters_text }
							onChange={ newState => setAttributes( { more_filters_text: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Min - Max Price Field?', 'directorist' ) }
							checked={ price_min_max_field }
							onChange={ newState => setAttributes( { price_min_max_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Price Range Field?', 'directorist' ) }
							checked={ price_range_field }
							onChange={ newState => setAttributes( { price_range_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Rating Field?', 'directorist' ) }
							checked={ rating_field }
							onChange={ newState => setAttributes( { rating_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Tag Field?', 'directorist' ) }
							checked={ tag_field }
							onChange={ newState => setAttributes( { tag_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Open Now Field?', 'directorist' ) }
							checked={ open_now_field }
							onChange={ newState => setAttributes( { open_now_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Custom Field Field?', 'directorist' ) }
							checked={ custom_fields }
							onChange={ newState => setAttributes( { custom_fields: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Website Field?', 'directorist' ) }
							checked={ website_field }
							onChange={ newState => setAttributes( { website_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Email Field?', 'directorist' ) }
							checked={ email_field }
							onChange={ newState => setAttributes( { email_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Phone Number Field?', 'directorist' ) }
							checked={ phone_field }
							onChange={ newState => setAttributes( { phone_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Fax Field?', 'directorist' ) }
							checked={ fax }
							onChange={ newState => setAttributes( { fax: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Address Field?', 'directorist' ) }
							checked={ address_field }
							onChange={ newState => setAttributes( { address_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Zip Field?', 'directorist' ) }
							checked={ zip_code_field }
							onChange={ newState => setAttributes( { zip_code_field: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Apply Filters Button?', 'directorist' ) }
							checked={ apply_filters_button }
							onChange={ newState => setAttributes( { apply_filters_button: newState } ) }
						/> : '' }
						{ more_filters_button && apply_filters_button ? <TextControl
							label={ __( 'Apply Filters Text', 'directorist' ) }
							type='text'
							value={ apply_filters_text }
							onChange={ newState => setAttributes( { apply_filters_text: newState } ) }
						/> : '' }
						{ more_filters_button ? <ToggleControl
							label={ __( 'Show Reset Filters Button?', 'directorist' ) }
							checked={ reset_filters_button }
							onChange={ newState => setAttributes( { reset_filters_button: newState } ) }
						/> : '' }
						{ more_filters_button && reset_filters_button ? <TextControl
							label={ __( 'Reset Filters Text', 'directorist' ) }
							type='text'
							value={ reset_filters_text }
							onChange={ newState => setAttributes( { reset_filters_text: newState } ) }
						/> : '' }
						<SelectControl
							label={ __( 'More Filter By', 'directorist' ) }
							labelPosition='side'
							value={ more_filters_display }
							options={ [
								{ label: __( 'Overlapping', 'directorist' ), value: 'overlapping' },
								{ label: __( 'Sliding', 'directorist' ), value: 'sliding' },
								{ label: __( 'Always Open', 'directorist' ), value: 'always_open' },
							] }
							onChange={ newState => setAttributes( { more_filters_display: newState } ) }
							className='directorist-gb-fixed-control'
						/>
						<ToggleControl
							label={ __( 'Logged In User Only?', 'directorist' ) }
							checked={ logged_in_user_only }
							onChange={ newState => setAttributes( { logged_in_user_only: newState } ) }
						/>
					</PanelBody>
				</InspectorControls>

				<div { ...useBlockProps() }>
					<ServerSideRender
						block='directorist/search-listing'
						attributes={ attributes }
					/>
				</div>
			</Fragment>
		);
	}
} );

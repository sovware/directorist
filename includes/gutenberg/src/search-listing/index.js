import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { TypesControl } from '../controls';

import {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';

import {
	getAttsForTransform,
	isMultiDirectoryEnabled,
	getWithSharedAttributes,
	getPreview
} from '../functions'
import blockAttributes from './attributes.json';
import getLogo from '../logo';
import './editor.scss';

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
				attributes: getAttsForTransform( blockAttributes )
			},
		]
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	attributes: getWithSharedAttributes( blockAttributes ),

	edit( { attributes, setAttributes } ) {
		if ( attributes.isPreview ) {
			return <Fragment>{ getPreview( 'search' ) }</Fragment>
		}

		let {
			show_title_subtitle,
			search_bar_title,
			search_bar_sub_title,
			more_filters_button,
			more_filters_text,
			reset_filters_button,
			apply_filters_button,
			reset_filters_text,
			apply_filters_text,
			more_filters_display,
			logged_in_user_only,
			directory_type,
			default_directory_type
		} = attributes;

		let oldTypes = directory_type ? directory_type.split(',') : [];

		const [ shouldRender, setShouldRender ] = useState( true );

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'General', 'directorist' ) } initialOpen={ true }>
						{ isMultiDirectoryEnabled() ? <TypesControl
							shouldRender={ shouldRender }
							selected={ oldTypes }
							showDefault={ true }
							defaultType={ default_directory_type }
							onDefaultChange={ value => setAttributes( { default_directory_type: value } ) }
							onChange={ types => {
								setAttributes( { directory_type: types.join( ',' ) } );
								setShouldRender( false );
							} }  /> : '' }

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

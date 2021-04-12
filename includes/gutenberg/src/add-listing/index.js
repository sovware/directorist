import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { Fragment, useState } from '@wordpress/element';
import { PanelBody } from '@wordpress/components';
import { TypesControl } from '../controls';

import {
	isMultiDirectoryEnabled,
	getWithSharedAttributes,
	getPreview
} from '../functions'
import blockAttributes from './attributes.json';
import './editor.scss';
import getLogo from './../logo';

registerBlockType( 'directorist/add-listing', {
	apiVersion: 2,

	title: __( 'Add Listing', 'directorist' ),

	description: __( 'Create a listing entry form.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false,
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_add_listing',
				attributes: {}
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
			return <Fragment>{ getPreview( 'add-listing' ) }</Fragment>
		}

		let { directory_type } = attributes;

		let oldTypes = directory_type ? directory_type.split(',') : [];
		const [ shouldRender, setShouldRender ] = useState( true );

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'General', 'directorist' ) } initialOpen={ true }>
						{ isMultiDirectoryEnabled() ? <TypesControl
							shouldRender={ shouldRender }
							selected={ oldTypes }
							showDefault={ false }
							onChange={ types => {
								setAttributes( { directory_type: types.join( ',' ) } );
								setShouldRender( false );
							} }  /> : '' }
					</PanelBody>
				</InspectorControls>

				<div { ...useBlockProps() }>
					<ServerSideRender
						block='directorist/add-listing'
						attributes={ attributes }
					/>
				</div>
			</Fragment>
		);
	}
} );

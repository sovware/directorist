import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { Fragment, useState } from '@wordpress/element';
import { PanelBody } from '@wordpress/components';
import { TypesControl } from './../controls';
import {
	isMultiDirectoryEnabled,
	getPreview
} from './../functions'
import metadata from './block.json';
import getLogo from './../logo';

registerBlockType( metadata.name, {
	icon: getLogo(),

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

	edit({attributes: {directory_type, isPreview}, setAttributes}) {
		const oldTypes = directory_type ? directory_type.split(',') : [];
		const [shouldRender, setShouldRender] = useState( true );

		return (
			<Fragment>
				{ isMultiDirectoryEnabled() && <InspectorControls>
					<PanelBody title={ __( 'General', 'directorist' ) } initialOpen={ true }>
						<TypesControl
							shouldRender={shouldRender}
							selected={oldTypes}
							showDefault={false}
							onChange={ types => {
								setAttributes( { directory_type: types.join( ',' ) } );
								setShouldRender(false);
							} }  />
					</PanelBody>
				</InspectorControls> }

				<div { ...useBlockProps() }>
					{ getPreview( 'add-listing', isPreview ) }
				</div>
			</Fragment>
		);
	}
} );

import { registerBlockType } from '@wordpress/blocks';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import {
	useBlockProps,
	InspectorControls,
} from '@wordpress/block-editor';

import {
	PanelBody,
	ToggleControl
} from '@wordpress/components';

import {
	getAttsForTransform,
	getPreview
} from './../functions'
import metadata from './block.json';
import getLogo from './../logo';

registerBlockType(metadata.name, {
	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_author_profile',
				attributes: getAttsForTransform( metadata.attributes )
			}
		]
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	edit( { attributes, setAttributes } ) {
		let { logged_in_user_only } = attributes;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Settings', 'directorist' ) } initialOpen={ true }>
						<ToggleControl
							label={ __( 'Logged In User Only?', 'directorist' ) }
							checked={ logged_in_user_only }
							onChange={ newState => setAttributes( { logged_in_user_only: newState } ) }
						/>
					</PanelBody>
				</InspectorControls>

				<div { ...useBlockProps() }>
					{ getPreview( 'author-profile', attributes.isPreview) }
				</div>
			</Fragment>
		);
	}
} );

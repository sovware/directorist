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
	getWithSharedAttributes,
	getPreview
} from '../functions'
import blockAttributes from './attributes.json';
import getLogo from '../logo';
import './editor.scss';

registerBlockType( 'directorist/author-profile', {
	apiVersion: 2,

	title: __( 'Author Profile', 'directorist' ),

	description: __( 'Create author profile section.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_author_profile',
				attributes: getAttsForTransform( blockAttributes )
			}
		]
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	attributes: getWithSharedAttributes( blockAttributes ),

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

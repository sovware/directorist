import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import {
	getWithSharedAttributes,
	getPreview
} from '../functions'
import './editor.scss';
import getLogo from './../logo';

registerBlockType( 'directorist/user-dashboard', {
	apiVersion: 2,

	title: __( 'Dashboard', 'directorist' ),

	description: __( 'Create user frontend dashboard.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false,
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_user_dashboard',
				attributes: {}
			},
		]
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	attributes: getWithSharedAttributes(),

	edit( { attributes } ) {
		return (
			<div { ...useBlockProps() }>
				{ getPreview( 'dashboard', attributes.isPreview ) }
			</div>
		);
	}
} );

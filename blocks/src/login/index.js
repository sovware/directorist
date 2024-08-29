import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import {
	getWithSharedAttributes,
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
				tag: 'directorist_user_login',
				attributes: {}
			},
		]
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	edit( { attributes } ) {
		return (
			<div { ...useBlockProps() }>
				{ getPreview( 'signin', attributes.isPreview) }
			</div>
		);
	}
} );

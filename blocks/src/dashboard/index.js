import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import {
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

	edit( { attributes } ) {
		return (
			<div { ...useBlockProps() }>
				{ getPreview( 'dashboard', attributes.isPreview ) }
			</div>
		);
	}
} );

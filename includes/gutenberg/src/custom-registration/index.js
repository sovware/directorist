import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './editor.scss';
import getLogo from './../logo';

registerBlockType( 'directorist/custom-registration', {
	apiVersion: 2,

	title: __( 'Registration', 'directorist' ),

	description: __( 'Create a custom registration page and this block only works on registration page set from settings.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false,
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_custom_registration',
				attributes: {}
			},
		]
	},

	edit( { attributes } ) {
		return (
			<div { ...useBlockProps() }>
				{ __( 'This widget works only on Registration page.', 'directorist' ) }
			</div>
		);
	}
} );

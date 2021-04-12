import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './editor.scss';
import getLogo from './../logo';

registerBlockType( 'directorist/user-login', {
	apiVersion: 2,

	title: __( 'Login', 'directorist' ),

	description: __( 'Create user login page and this block only works on login page set from settings.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false,
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_user_login',
				attributes: {}
			},
		]
	},

	edit( { attributes } ) {
		return (
			<div { ...useBlockProps() }>
				{ __( 'This block works only on Login page.', 'directorist' ) }
			</div>
		);
	}
} );

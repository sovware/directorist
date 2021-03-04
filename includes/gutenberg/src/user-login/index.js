import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import './editor.scss';

registerBlockType( 'directorist/user-login', {
	apiVersion: 2,

	title: __( 'Login', 'directorist' ),

	description: __( 'This block works only on Login page.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z" /><path d="M19 13H5v-2h14v2z" /></svg>,

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

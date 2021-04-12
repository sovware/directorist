import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

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

	edit( { attributes } ) {
		return (
			<div { ...useBlockProps() }>
				<ServerSideRender
					block='directorist/add-listing'
					attributes={ attributes }
				/>
			</div>
		);
	}
} );

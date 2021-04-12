import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

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
		if ( attributes.isPreview ) {
			return <Fragment>{ getPreview( 'dashboard' ) }</Fragment>
		}

		return (
			<div { ...useBlockProps() }>
				<ServerSideRender
					block='directorist/user-dashboard'
					attributes={ attributes }
				/>
			</div>
		);
	}
} );

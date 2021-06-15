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

	edit() {
		return (
			<div { ...useBlockProps() }>
				<div>{ getPreview( 'dashboard' ) }</div>
				<div style={{textAlign: 'center', fontSize: '12px', marginTop: '5px'}}><em>It's a placeholder. Please check the preview on frontend.</em></div>
			</div>
		);
	}
} );

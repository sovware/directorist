import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import {
	getWithSharedAttributes,
	getPreview
} from '../functions'
import './editor.scss';
import getLogo from '../logo';

registerBlockType( 'directorist/single-listing', {
	apiVersion: 2,

	title: __( 'Single Listing', 'directorist' ),

	description: __( 'Create a single listing details page.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false,
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
				{ getPreview( 'single-listing', attributes.isPreview) }
			</div>
		);
	}
} );

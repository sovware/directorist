import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import {
	getWithSharedAttributes,
	getPreview
} from './../functions'
import metadata from './block.json';
import getLogo from './../logo';

registerBlockType( metadata.name, {

	icon: getLogo(),

	supports: {
		html: false,
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	edit( { attributes } ) {
		return (
			<div { ...useBlockProps() }>
				<div style={{textAlign: 'center', fontSize: '12px'}}><em>{ __( 'Directory Builder can be used to change or modify single listing view.', 'directorist' ) }</em></div>
				{ getPreview( 'single-listing', attributes.isPreview) }
			</div>
		);
	}
} );

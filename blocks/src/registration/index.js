import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { getPreview } from './../functions'
import metadata from './block.json';
import getLogo from './../logo';

registerBlockType( metadata.name, {
	
	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_custom_registration',
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
				{ getPreview( 'signin', attributes.isPreview ) }
			</div>
		);
	}
} );

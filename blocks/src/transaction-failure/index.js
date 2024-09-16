import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import getLogo from './../logo';

registerBlockType( metadata.name, {

	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_transaction_failure',
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
				<div style={ { paddingLeft: '10em', paddingRight: '10em' } }>{ getLogo() }</div>
			</div>
		);
	}
} );

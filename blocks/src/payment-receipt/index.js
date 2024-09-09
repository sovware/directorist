import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import {
	getWithSharedAttributes
} from './../functions'

import metadata from './block.json';
import getLogo from './../logo';

registerBlockType( metadata.name, {

	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_payment_receipt',
				attributes: {}
			},
		]
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	edit() {
		return (
			<div { ...useBlockProps() }>
				<div style={ { paddingLeft: '10em', paddingRight: '10em' } }>{ getLogo() }</div>
			</div>
		);
	}
} );

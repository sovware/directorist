import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import {
	getWithSharedAttributes
} from '../functions'
import './editor.scss';
import getLogo from './../logo';

registerBlockType( 'directorist/payment-receipt', {
	apiVersion: 2,

	title: __( 'Payment Receipt', 'directorist' ),

	description: __( 'Create a payment receipt page and this block only works on payment receipt page set from settings.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false,
	},

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

	attributes: getWithSharedAttributes(),

	edit( { attributes } ) {
		if ( attributes.isPreview ) {
			return <div style={ { paddingLeft: '10em', paddingRight: '10em' } }>{ getLogo() }</div>
		}

		return (
			<div { ...useBlockProps() }>
				{ __( 'This widget works only on Payment Receipt page.', 'directorist' ) }
			</div>
		);
	}
} );

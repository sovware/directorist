import { registerBlockType } from '@wordpress/blocks';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import {
	useBlockProps,
	InspectorControls,
} from '@wordpress/block-editor';

import {
	PanelBody,
	ToggleControl
} from '@wordpress/components';

import blockAttributesMap from './attributes.json';
import './editor.scss';
import getLogo from './../logo';

let transformAttributesMap = {};

for ( const [key, value] of Object.entries( blockAttributesMap ) ) {
	transformAttributesMap[key] = {
		type: value.type,
		shortcode: ({named}) => {
			if (typeof named[key] === 'undefined' ) {
				return value.default;
			}

			if (value.type === 'string') {
				return String(named[key]);
			}

			if (value.type === 'number') {
				return Number(named[key]);
			}

			if (value.type === 'boolen') {
				return Boolean(named[key]);
			}
		}
	}
}

registerBlockType( 'directorist/author-profile', {
	apiVersion: 2,

	title: __( 'Author Profile', 'directorist' ),

	description: __( 'Create author profile section.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_author_profile',
				attributes: transformAttributesMap
			}
		]
	},

	attributes: blockAttributesMap,

	edit( { attributes, setAttributes } ) {
		let { logged_in_user_only } = attributes;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Settings', 'directorist' ) } initialOpen={ true }>
						<ToggleControl
							label={ __( 'Logged In User Only?', 'directorist' ) }
							checked={ logged_in_user_only }
							onChange={ newState => setAttributes( { logged_in_user_only: newState } ) }
						/>
					</PanelBody>
				</InspectorControls>

				<div { ...useBlockProps() }>
					<ServerSideRender
						block='directorist/author-profile'
						attributes={ attributes }
					/>
				</div>
			</Fragment>
		);
	}
} );

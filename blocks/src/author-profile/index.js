import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import { PanelBody, ToggleControl } from '@wordpress/components';

import { getAttsForTransform, getPlaceholder } from './../functions';
import metadata from './block.json';
import getLogo from './../logo';

const Placeholder = () => getPlaceholder( 'author-profile' );

registerBlockType( metadata.name, {
	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_author_profile',
				attributes: getAttsForTransform( metadata.attributes ),
			},
		],
	},

	edit( { attributes, setAttributes } ) {
		let { logged_in_user_only } = attributes;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={ __( 'Settings', 'directorist' ) }
						initialOpen={ true }
					>
						<ToggleControl
							label={ __(
								'Logged In User Can View Only',
								'directorist'
							) }
							checked={ logged_in_user_only }
							onChange={ ( newState ) =>
								setAttributes( {
									logged_in_user_only: newState,
								} )
							}
						/>
					</PanelBody>
				</InspectorControls>

				<div
					{ ...useBlockProps( {
						className:
							'directorist-content-active directorist-w-100',
					} ) }
				>
					<ServerSideRender
						block={ metadata.name }
						attributes={ attributes }
						LoadingResponsePlaceholder={ Placeholder }
					/>
				</div>
			</Fragment>
		);
	},
} );

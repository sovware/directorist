import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import { Fragment, useState } from '@wordpress/element';
import { PanelBody } from '@wordpress/components';
import { TypesControl } from './../controls';
import { isMultiDirectoryEnabled, getPlaceholder } from './../functions';
import metadata from './block.json';
import getLogo from './../logo';

const Placeholder = () => getPlaceholder( 'add-listing' );

registerBlockType( metadata.name, {
	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_add_listing',
				attributes: {},
			},
		],
	},

	edit( { attributes, setAttributes } ) {
		const [ shouldRender, setShouldRender ] = useState( true );

		const oldTypes = attributes.directory_type
			? attributes.directory_type.split( ',' )
			: [];

		return (
			<Fragment>
				{ isMultiDirectoryEnabled() && (
					<InspectorControls>
						<PanelBody
							title={ __( 'General', 'directorist' ) }
							initialOpen={ true }
						>
							<TypesControl
								shouldRender={ shouldRender }
								selected={ oldTypes }
								showDefault={ false }
								onChange={ ( types ) => {
									setAttributes( {
										directory_type: types.join( ',' ),
									} );
									setShouldRender( false );
								} }
							/>
						</PanelBody>
					</InspectorControls>
				) }

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

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { getPlaceholder } from '../functions';
import metadata from './block.json';
import getLogo from '../logo';

const Placeholder = () => getPlaceholder( 'author-profile' );

registerBlockType( metadata.name, {

	icon: getLogo(),

	edit( { attributes } ) {
		return (
			<Fragment>
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

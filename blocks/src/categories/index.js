import { registerBlockType, createBlock } from '@wordpress/blocks';
import {
	useBlockProps,
	InspectorControls,
	BlockControls,
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { Fragment, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { CategoryControl, TypesControl } from './../controls';

import { list, grid } from '@wordpress/icons';

import {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
	ToolbarGroup,
	ToolbarButton,
} from '@wordpress/components';

import {
	getAttsForTransform,
	isMultiDirectoryEnabled,
	getPlaceholder,
} from './../functions';
import metadata from './block.json';
import getLogo from './../logo';

const Placeholder = () => getPlaceholder( 'categories-grid' );

registerBlockType( metadata.name, {
	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_all_categories',
				attributes: getAttsForTransform( metadata.attributes ),
			},
			{
				type: 'block',
				blocks: [ 'directorist/all-locations' ],
				transform: ( attributes ) => {
					attributes.cat_per_page = attributes.loc_per_page;
					attributes.slug = '';
					delete attributes.loc_per_page;
					return createBlock(
						'directorist/all-categories',
						attributes
					);
				},
			},
		],
	},

	edit( { attributes, setAttributes } ) {
		const [ shouldRender, setShouldRender ] = useState( true );

		let {
			view,
			orderby,
			order,
			cat_per_page,
			columns,
			slug,
			logged_in_user_only,
			redirect_page_url,
			directory_type,
			default_directory_type,
		} = attributes;

		let oldCategories = slug ? slug.split( ',' ) : [],
			oldTypes = directory_type ? directory_type.split( ',' ) : [];

		return (
			<Fragment>
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton
							isPressed={ view === 'grid' }
							icon={ grid }
							label={ __( 'Grid View', 'directorist' ) }
							onClick={ () => setAttributes( { view: 'grid' } ) }
						/>
						<ToolbarButton
							isPressed={ view === 'list' }
							icon={ list }
							label={ __( 'List View', 'directorist' ) }
							onClick={ () => setAttributes( { view: 'list' } ) }
						/>
					</ToolbarGroup>
				</BlockControls>

				<InspectorControls>
					<PanelBody
						title={ __( 'General', 'directorist' ) }
						initialOpen={ true }
					>
						{ isMultiDirectoryEnabled() ? (
							<TypesControl
								shouldRender={ shouldRender }
								selected={ oldTypes }
								showDefault={ true }
								defaultType={ default_directory_type }
								onDefaultChange={ ( value ) =>
									setAttributes( {
										default_directory_type: value,
									} )
								}
								onChange={ ( types ) => {
									setAttributes( {
										directory_type: types.join( ',' ),
									} );

									if ( types.length === 1 ) {
										setAttributes( {
											default_directory_type: types[0],
										} );
									}

									setShouldRender( false );
								} }
							/>
						) : (
							''
						) }

						<SelectControl
							label={ __( 'Default View', 'directorist' ) }
							labelPosition="side"
							value={ view }
							options={ [
								{
									label: __( 'Grid', 'directorist' ),
									value: 'grid',
								},
								{
									label: __( 'List', 'directorist' ),
									value: 'list',
								},
							] }
							onChange={ ( view ) => setAttributes( { view } ) }
							className="directorist-gb-fixed-control"
						/>
						{ view === 'grid' ? (
							<SelectControl
								label={ __( 'Columns', 'directorist' ) }
								labelPosition="side"
								value={ columns }
								options={ [
									{
										label: __( '1 Column', 'directorist' ),
										value: 1,
									},
									{
										label: __( '2 Columns', 'directorist' ),
										value: 2,
									},
									{
										label: __( '3 Columns', 'directorist' ),
										value: 3,
									},
									{
										label: __( '4 Columns', 'directorist' ),
										value: 4,
									},
									{
										label: __( '6 Columns', 'directorist' ),
										value: 6,
									},
								] }
								onChange={ ( newColumns ) =>
									setAttributes( {
										columns: Number( newColumns ),
									} )
								}
								className="directorist-gb-fixed-control"
							/>
						) : (
							''
						) }
						<TextControl
							label={ __( 'Number Of Categories', 'directorist' ) }
							type="number"
							value={ cat_per_page }
							onChange={ ( perPage ) =>
								setAttributes( {
									cat_per_page: Number( perPage ),
								} )
							}
							className="directorist-gb-fixed-control"
							help={ __(
								'Set the number of categories to show.',
								'directorist'
							) }
						/>
						<SelectControl
							label={ __( 'Order By', 'directorist' ) }
							labelPosition="side"
							value={ orderby }
							options={ [
								{
									label: __( 'ID', 'directorist' ),
									value: 'id',
								},
								{
									label: __( 'Count', 'directorist' ),
									value: 'count',
								},
								{
									label: __( 'Name', 'directorist' ),
									value: 'name',
								},
								{
									label: __( 'Categories', 'directorist' ),
									value: 'slug',
								},
							] }
							onChange={ ( orderby ) =>
								setAttributes( { orderby } )
							}
							className="directorist-gb-fixed-control"
						/>
						{ orderby === 'slug' ? (
							<CategoryControl
								onChange={ ( categories ) => {
									setAttributes( {
										slug: categories.join( ',' ),
									} );
								} }
								value={ oldCategories }
							/>
						) : (
							''
						) }
						<SelectControl
							label={ __( 'Order', 'directorist' ) }
							labelPosition="side"
							value={ order }
							options={ [
								{
									label: __( 'ASC', 'directorist' ),
									value: 'asc',
								},
								{
									label: __( 'DESC', 'directorist' ),
									value: 'desc',
								},
							] }
							onChange={ ( order ) => setAttributes( { order } ) }
							className="directorist-gb-fixed-control"
						/>
						<ToggleControl
							label={ __(
								'LoggedIn User Can View Only',
								'directorist'
							) }
							checked={ logged_in_user_only }
							onChange={ ( logged_in_user_only ) =>
								setAttributes( { logged_in_user_only } )
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

import { registerBlockType, createBlock } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls } from '@wordpress/block-editor';
import { Fragment, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { LocationControl, TypesControl } from './../controls';

import {
	list,
	grid,
} from '@wordpress/icons';

import {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
	Toolbar,
	ToolbarButton,
} from '@wordpress/components';

import {
	getAttsForTransform,
	isMultiDirectoryEnabled,
	getWithSharedAttributes,
	getPreview
} from './../functions'
import metadata from './block.json';
import getLogo from './../logo';

registerBlockType( metadata.name, {

	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_all_locations',
				attributes: getAttsForTransform( metadata.attributes )
			},
			{
				type: 'block',
				blocks: [ 'directorist/all-categories' ],
				transform: ( attributes ) => {
					attributes.loc_per_page = attributes.cat_per_page;
					attributes.slug = '';
					delete attributes.cat_per_page;
					return createBlock( 'directorist/all-locations', attributes );
				},
			},
		]
	},

	example: {
		attributes: {
			isPreview: true
		}
	},

	edit( { attributes, setAttributes } ) {
		let {
			view,
			orderby,
			order,
			loc_per_page,
			columns,
			slug,
			logged_in_user_only,
			redirect_page_url,
			directory_type,
			default_directory_type
		} = attributes;

		let oldLocations = slug ? slug.split(',') : [],
			oldTypes = directory_type ? directory_type.split(',') : [];

		const [ shouldRender, setShouldRender ] = useState( true );

		return (
			<Fragment>
				<BlockControls>
					<Toolbar>
						<ToolbarButton isPressed={view === 'grid'} icon={ grid } label={ __( 'Grid View', 'directorist' ) } onClick={ () => setAttributes( { view: 'grid' } ) } />
						<ToolbarButton isPressed={view === 'list'} icon={ list } label={ __( 'List View', 'directorist' ) } onClick={ () => setAttributes( { view: 'list' } ) } />
					</Toolbar>
				</BlockControls>

				<InspectorControls>
					<PanelBody title={ __( 'General', 'directorist' ) } initialOpen={ true }>
						{ isMultiDirectoryEnabled() ? <TypesControl
							shouldRender={ shouldRender }
							selected={ oldTypes }
							showDefault={ true }
							defaultType={ default_directory_type }
							onDefaultChange={ value => setAttributes( { default_directory_type: value } ) }
							onChange={ types => {
								setAttributes( { directory_type: types.join( ',' ) } );
								setShouldRender( false );
							} }  /> : '' }

						<SelectControl
							label={ __( 'View As', 'directorist' ) }
							labelPosition='side'
							value={ view }
							options={ [
								{ label: __( 'Grid', 'directorist' ), value: 'grid' },
								{ label: __( 'List', 'directorist' ), value: 'list' },
							] }
							onChange={ newState => setAttributes( { view: newState } ) }
							className='directorist-gb-fixed-control'
						/>
						{ view === 'grid' ? <SelectControl
							label={ __( 'Columns', 'directorist' ) }
							labelPosition='side'
							value={ columns }
							options={ [
								{ label: __( '1 Column', 'directorist' ), value: 1 },
								{ label: __( '2 Columns', 'directorist' ), value: 2 },
								{ label: __( '3 Columns', 'directorist' ), value: 3 },
								{ label: __( '4 Columns', 'directorist' ), value: 4 },
								{ label: __( '6 Columns', 'directorist' ), value: 6 },
							] }
							onChange={ newState => setAttributes( { columns: Number(newState) } ) }
							className='directorist-gb-fixed-control'
						/> : '' }
						<TextControl
							label={ __( 'Listings Per Page', 'directorist' ) }
							type='number'
							value={ loc_per_page }
							onChange={ newState => setAttributes( { loc_per_page: Number(newState) } ) }
							className='directorist-gb-fixed-control'
							help={ __( 'Set the number of listings to show per page.', 'directorist' ) }
						/>
						<SelectControl
							label={ __( 'Order By', 'directorist' ) }
							labelPosition='side'
							value={ orderby }
							options={ [
								{ label: __( 'ID', 'directorist' ), value: 'id' },
								{ label: __( 'Count', 'directorist' ), value: 'count' },
								{ label: __( 'Name', 'directorist' ), value: 'name' },
								{ label: __( 'Locations', 'directorist' ), value: 'slug' },
							] }
							onChange={ newState => setAttributes( { orderby: newState } ) }
							className='directorist-gb-fixed-control'
						/>
						{ orderby === 'slug' ? <LocationControl onChange={ locations => {
							setAttributes( { slug: locations.join( ',' ) } );
						}} value={ oldLocations } /> : '' }
						<SelectControl
							label={ __( 'Order', 'directorist' ) }
							labelPosition='side'
							value={ order }
							options={ [
								{ label: __( 'ASC', 'directorist' ), value: 'asc' },
								{ label: __( 'DESC', 'directorist' ), value: 'desc' },
							] }
							onChange={ newState => setAttributes( { order: newState } ) }
							className='directorist-gb-fixed-control'
						/>
						<ToggleControl
							label={ __( 'Logged In User Only?', 'directorist' ) }
							checked={ logged_in_user_only }
							onChange={ newState => setAttributes( { logged_in_user_only: newState } ) }
						/>
					</PanelBody>
				</InspectorControls>

				<div { ...useBlockProps() }>
					{ getPreview( 'locations-grid', attributes.isPreview ) }
				</div>
			</Fragment>
		);
	}
} );

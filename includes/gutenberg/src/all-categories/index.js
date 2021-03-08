import { registerBlockType, createBlock } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { CategoryControl } from './../all-listing/controls';

import {
	list,
	grid,
} from '@wordpress/icons';

import {
	PanelBody,
	PanelRow,
	SelectControl,
	ToggleControl,
	TextControl,
	Toolbar,
	ToolbarButton,
} from '@wordpress/components';

import blockAttributesMap from './attributes';
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

registerBlockType( 'directorist/all-categories', {
	apiVersion: 2,

	title: __( 'All Categories', 'directorist' ),

	description: __( 'Create directory category listing grid view and list view.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: getLogo(),

	supports: {
		html: false
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_all_categories',
				attributes: transformAttributesMap
			},
			{
				type: 'block',
				blocks: [ 'directorist/all-locations' ],
				transform: ( attributes ) => {
					attributes.cat_per_page = attributes.loc_per_page;
					attributes.slug = '';
					delete attributes.loc_per_page;
					return createBlock( 'directorist/all-categories', attributes );
				},
			},
		]
	},

	attributes: blockAttributesMap,

	edit( { attributes, setAttributes } ) {
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
			default_directory_type
		} = attributes;

		let oldCategories = slug ? slug.split(',') : [];

		return (
			<Fragment>
				<BlockControls>
					<Toolbar>
						<ToolbarButton isPressed={view === 'grid'} icon={ grid } label={ __( 'Grid View', 'directorist' ) } onClick={ () => setAttributes( { view: 'grid' } ) } />
						<ToolbarButton isPressed={view === 'list'} icon={ list } label={ __( 'List View', 'directorist' ) } onClick={ () => setAttributes( { view: 'list' } ) } />
					</Toolbar>
				</BlockControls>
				
				<InspectorControls>
					<PanelBody title={ __( 'Listing Layout', 'directorist' ) } initialOpen={ true }>
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
							onChange={ newState => setAttributes( { columns: newState } ) }
							className='directorist-gb-fixed-control'
						/> : '' }
						<TextControl
							label={ __( 'Number Of Listing', 'directorist' ) }
							type='number'
							value={ cat_per_page }
							onChange={ newState => setAttributes( { cat_per_page: newState } ) }
							className='directorist-gb-fixed-control'
						/>
						<ToggleControl
							label={ __( 'Logged In User Only?', 'directorist' ) }
							checked={ logged_in_user_only }
							onChange={ newState => setAttributes( { logged_in_user_only: newState } ) }
						/>
					</PanelBody>

					<PanelBody title={ __( 'Listing Query', 'directorist' ) } initialOpen={ false }>
						<SelectControl
							label={ __( 'Order By', 'directorist' ) }
							labelPosition='side'
							value={ orderby }
							options={ [
								{ label: __( 'ID', 'directorist' ), value: 'id' },
								{ label: __( 'Count', 'directorist' ), value: 'count' },
								{ label: __( 'Name', 'directorist' ), value: 'name' },
								{ label: __( 'Categories', 'directorist' ), value: 'slug' },
							] }
							onChange={ newState => setAttributes( { orderby: newState } ) }
							className='directorist-gb-fixed-control'
						/>
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
						<CategoryControl onChange={(added, newCategory) => {
							let _categories = oldCategories.slice(0);

							if (added) {
								_categories.push(newCategory);
							} else {
								_categories.splice(_categories.indexOf(newCategory), 1);
							}
							
							setAttributes({slug: _categories.join(',')});
						}} selected={oldCategories} />
					</PanelBody>
				</InspectorControls>

				<div { ...useBlockProps() }>
					<ServerSideRender
						block="directorist/all-categories"
						attributes={ attributes }
					/>
				</div>
			</Fragment>
		);
	}
} );

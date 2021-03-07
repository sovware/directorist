import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { LocationControl, CategoryControl, TagsControl, ListingControl } from './controls';

import {
	list,
	grid,
	mapMarker
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

registerBlockType( 'directorist/all-listing', {
	apiVersion: 2,

	title: __( 'All Listing', 'directorist' ),

	description: __( 'Create directory listing grid view, list view or map view.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z" /><path d="M19 13H5v-2h14v2z" /></svg>,

	supports: {
		html: false
		// className: false
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_all_listing',
				attributes: transformAttributesMap
			},
		]
	},

	attributes: blockAttributesMap,

	edit( { attributes, setAttributes } ) {
		let {
			view,
			_featured,
			filterby,
			orderby,
			order,
			listings_per_page,
			show_pagination,
			header,
			header_title,
			category,
			location,
			tag,
			ids,
			columns,
			featured_only,
			popular_only,
			advanced_filter,
			display_preview_image,
			action_before_after_loop,
			logged_in_user_only,
			map_height,
			map_zoom_level,
			directory_type,
			default_directory_type
		} = attributes;

		let oldLocations = location ? location.split(',') : [],
			oldCategories = category ? category.split(',') : [],
			oldTags = tag ? tag.split(',') : [],
			oldIds = ids ? ids.split(',').map(id => Number(id)) : [];

		return (
			<Fragment>
				<BlockControls>
					<Toolbar>
						<ToolbarButton isPressed={view === 'grid'} icon={ grid } label={ __( 'Grid View', 'directorist' ) } onClick={ () => setAttributes( { view: 'grid' } ) } />
						<ToolbarButton isPressed={view === 'list'} icon={ list } label={ __( 'List View', 'directorist' ) } onClick={ () => setAttributes( { view: 'list' } ) } />
						<ToolbarButton isPressed={view === 'map'} icon={ mapMarker } label={ __( 'Map View', 'directorist' ) } onClick={ () => setAttributes( { view: 'map' } ) } />
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
								{ label: __( 'Map', 'directorist' ), value: 'map' },
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
							value={ listings_per_page }
							onChange={ newState => setAttributes( { listings_per_page: newState } ) }
							className='directorist-gb-fixed-control'
						/>
						<ToggleControl
							label={ __( 'Show Pagination?', 'directorist' ) }
							checked={ show_pagination }
							onChange={ newState => setAttributes( { show_pagination: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Featured Only?', 'directorist' ) }
							checked={ featured_only }
							onChange={ newState => setAttributes( { featured_only: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Header?', 'directorist' ) }
							checked={ header }
							onChange={ newState => setAttributes( { header: newState } ) }
						/>
						{ header ? <TextControl
							label={ __( 'Header Title', 'directorist' ) }
							type='text'
							value={ header_title }
							onChange={ newState => setAttributes( { header_title: newState } ) }
						/> : setAttributes( { header_title: '' } ) }
						<ToggleControl
							label={ __( 'Show Popular Only?', 'directorist' ) }
							checked={ popular_only }
							onChange={ newState => setAttributes( { popular_only: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Filter Button?', 'directorist' ) }
							checked={ advanced_filter }
							onChange={ newState => setAttributes( { advanced_filter: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Preview Image?', 'directorist' ) }
							checked={ display_preview_image }
							onChange={ newState => setAttributes( { display_preview_image: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Enable Before After Hook?', 'directorist' ) }
							checked={ action_before_after_loop }
							onChange={ newState => setAttributes( { action_before_after_loop: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Logged In User Only?', 'directorist' ) }
							checked={ logged_in_user_only }
							onChange={ newState => setAttributes( { logged_in_user_only: newState } ) }
						/>
						{ view === 'map' ? <TextControl
							label={ __( 'Map Height', 'directorist' ) }
							type='number'
							value={ map_height }
							help={ __( 'Applicable for map view only', 'directorist' ) }
							onChange={ newState => setAttributes( { map_height: newState } ) }
							className={`directorist-gb-fixed-control ${view !== 'map' ? 'hidden' : ''}`}
						/> : '' }
						{ view === 'map' ? <TextControl
							label={ __( 'Map Zoom Level', 'directorist' ) }
							help={ __( 'Applicable for map view only', 'directorist' ) }
							type='number'
							value={ map_zoom_level }
							onChange={ newState => setAttributes( { map_zoom_level: newState } ) }
							className='directorist-gb-fixed-control'
						/> : '' }
					</PanelBody>

					<PanelBody title={ __( 'Listing Query', 'directorist' ) } initialOpen={ false }>
						<SelectControl
							label={ __( 'Order By', 'directorist' ) }
							labelPosition='side'
							value={ orderby }
							options={ [
								{ label: __( 'Title', 'directorist' ), value: 'title' },
								{ label: __( 'Date', 'directorist' ), value: 'date' },
								{ label: __( 'Random', 'directorist' ), value: 'rand' },
								{ label: __( 'Price', 'directorist' ), value: 'price' },
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

						<ListingControl onChange={(added, newId) => {
							let _ids = oldIds.slice(0);

							if (added) {
								_ids.push(newId);
							} else {
								_ids.splice(_ids.indexOf(newId), 1);
							}
							
							setAttributes({ids: _ids.join(',')});
						}} selected={oldIds} />

						<CategoryControl onChange={(added, newCategory) => {
							let _categories = oldCategories.slice(0);

							if (added) {
								_categories.push(newCategory);
							} else {
								_categories.splice(_categories.indexOf(newCategory), 1);
							}
							
							setAttributes({category: _categories.join(',')});
						}} selected={oldCategories} />

						<TagsControl onChange={(added, newTag) => {
							let _tags = oldTags.slice(0);

							if (added) {
								_tags.push(newTag);
							} else {
								_tags.splice(_tags.indexOf(newTag), 1);
							}
							
							setAttributes({tag: _tags.join(',')});
						}} selected={oldTags} />

						<LocationControl onChange={(added, newLocation) => {
							let _locations = oldLocations.slice(0);

							if (added) {
								_locations.push(newLocation);
							} else {
								_locations.splice(_locations.indexOf(newLocation), 1);
							}
							
							setAttributes({location: _locations.join(',')});
						}} selected={oldLocations} />
					</PanelBody>
				</InspectorControls>

				<div { ...useBlockProps() }>
					<ServerSideRender
						block='directorist/all-listing'
						attributes={ attributes }
					/>
				</div>
			</Fragment>
		);
	}
} );

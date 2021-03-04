import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import {
	PanelBody,
	PanelRow,
	SelectControl,
	ToggleControl,
	TextControl
} from '@wordpress/components';

import './editor.scss';

registerBlockType( 'directorist/all-listing', {
	apiVersion: 2,

	title: __( 'All Listing', 'directorist' ),

	description: __( 'Create directory listing grid view, list view or map view.', 'directorist' ),

	category: 'directorist-blocks-collection',

	icon: <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z" /><path d="M19 13H5v-2h14v2z" /></svg>,

	supports: {
		html: false,
		className: false
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_all_listing',
				attributes: {}
			},
		]
	},

	attributes: {
		view: {
			type: 'string',
			default: 'grid'
		},
		featured: {
			type: 'boolean',
			default: false
		},
		filterby: {
			type: 'string',
			default: ''
		},
		orderby: {
			type: 'string',
			default: 'date'
		},
		order: {
			type: 'string',
			default: 'desc'
		},
		per_page: {
			type: 'number',
			default: 6
		},
		pagination: {
			type: 'boolean',
			default: false
		},
		header: {
			type: 'boolean',
			default: false
		},
		header_title: {
			type: 'string',
			default: ''
		},
		category: {
			type: 'array',
			default: []
		},
		location: {
			type: 'array',
			default: []
		},
		tag: {
			type: 'array',
			default: []
		},
		ids: {
			type: 'string',
			default: ''
		},
		columns: {
			type: 'number',
			default: 3
		},
		featured_only: {
			type: 'boolean',
			default: false
		},
		popular_only: {
			type: 'boolean',
			default: false
		},
		filter: {
			type: 'boolean',
			default: false
		},
		preview_image: {
			type: 'boolean',
			default: false
		},
		loop_hook: {
			type: 'boolean',
			default: false
		},
		logged_in_only: {
			type: 'boolean',
			default: false
		},
		map_height: {
			type: 'number',
			default: 500
		},
		map_zoom_level: {
			type: 'number',
			default: 0
		},
		directory_type: {
			type: 'array',
			default: []
		},
		default_directory_type: {
			type: 'number',
			default: 0
		}
	},

	edit( { attributes, setAttributes } ) {
		let {
			view,
			filterby,
			orderby,
			order,
			per_page,
			pagination,
			header,
			header_title,
			category,
			location,
			tag,
			ids,
			columns,
			featured_only,
			popular_only,
			filter,
			preview_image,
			loop_hook,
			logged_in_only,
			map_height,
			map_zoom_level,
			directory_type,
			default_directory_type
		} = attributes;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Settings', 'directorist' ) } initialOpen={ true }>
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

						<TextControl
							label={ __( 'Number Of Listing', 'directorist' ) }
							type='number'
							value={ per_page }
							onChange={ newState => setAttributes( { per_page: newState } ) }
							className='directorist-gb-fixed-control'
						/>

						<TextControl
							label={ __( 'Listing IDs', 'directorist' ) }
							help={ __( 'Comma separated listing ids, eg: 1,3,4,6', 'directorist' ) }
							type='text'
							value={ ids }
							onChange={ newState => setAttributes( { ids: newState } ) }
						/>

						<ToggleControl
							label={ __( 'Show Header?', 'directorist' ) }
							checked={ header }
							onChange={ newState => setAttributes( { header: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Pagination', 'directorist' ) }
							checked={ pagination }
							onChange={ newState => setAttributes( { pagination: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Featured Only?', 'directorist' ) }
							checked={ featured_only }
							onChange={ newState => setAttributes( { featured_only: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Popular Only?', 'directorist' ) }
							checked={ popular_only }
							onChange={ newState => setAttributes( { popular_only: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Filter Button?', 'directorist' ) }
							checked={ filter }
							onChange={ newState => setAttributes( { filter: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Show Preview Image?', 'directorist' ) }
							checked={ preview_image }
							onChange={ newState => setAttributes( { preview_image: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Enable Look Hook', 'directorist' ) }
							checked={ loop_hook }
							onChange={ newState => setAttributes( { loop_hook: newState } ) }
						/>
						<ToggleControl
							label={ __( 'Logged In User Only?', 'directorist' ) }
							checked={ logged_in_only }
							onChange={ newState => setAttributes( { logged_in_only: newState } ) }
						/>
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

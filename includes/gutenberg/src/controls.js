import { withSelect, select } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { sortItemsBySelected } from './functions'
import { without, truncate } from 'lodash';
import { Fragment } from '@wordpress/element';

const {
	postType: POST_TYPE,
	locationTax: LOCATION_TAX,
	tagTax: TAG_TAX,
	categoryTax: CATEGORY_TAX,
	typeTax: TYPE_TAX
} = directoristBlockConfig;

import {
	CheckboxControl,
	RadioControl,
	BaseControl,
	Spinner
} from '@wordpress/components';

export const LocationControl = withSelect( select => {
	const args = {
		per_page: -1,
		order: 'asc',
		orderby: 'name'
	}

	return {
		items: select('core').getEntityRecords( 'taxonomy', LOCATION_TAX, args )
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		choices = ( props.shouldRender ? sortItemsBySelected( props.items, props.selected, 'slug' ) : props.items ).map( item => (
			<li key={ item.id }>
				<CheckboxControl
					label={ truncate( item.name, { length: 30 } ) }
					checked={ props.selected.includes( item.slug ) }
					onChange={ updated => {
						const values = updated ? [ ...props.selected, item.slug ] : without( props.selected, item.slug );

						props.onChange( values );
					} }
				/>
			</li>
		));
	} else {
		choices = [ <li><Spinner /></li> ]
	}
	
	return (
		<BaseControl label={ __( 'Locations', 'directorist' ) } className="directorist-gb-cb-list-control">
			<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
		</BaseControl>
	);
});

export const CategoryControl = withSelect( select => {
	const args = {
		per_page: -1,
		order: 'asc',
		orderby: 'name'
	}

	return {
		items: select('core').getEntityRecords( 'taxonomy', CATEGORY_TAX, args )
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		choices = ( props.shouldRender ? sortItemsBySelected( props.items, props.selected, 'slug' ) : props.items ).map( item => (
			<li key={ item.id }>
				<CheckboxControl
					label={ truncate( item.name, { length: 30 } ) }
					checked={ props.selected.includes( item.slug ) }
					onChange={ updated => {
						const values = updated ? [ ...props.selected, item.slug ] : without( props.selected, item.slug );

						props.onChange( values );
					} }
				/>
			</li>
		));
	} else {
		choices = [ <li><Spinner /></li> ]
	}
	
	return (
		<BaseControl label={ __( 'Categories', 'directorist' ) } className="directorist-gb-cb-list-control">
			<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
		</BaseControl>
	);
});

export const TagsControl = withSelect( select => {
	const args = {
		per_page: -1,
		order: 'asc',
		orderby: 'name'
	}

	return {
		items: select('core').getEntityRecords( 'taxonomy', TAG_TAX, args )
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		choices = ( props.shouldRender ? sortItemsBySelected( props.items, props.selected, 'slug' ) : props.items ).map( item => (
			<li key={ item.id }>
				<CheckboxControl
					label={ truncate( item.name, { length: 30 } ) }
					checked={ props.selected.includes( item.slug ) }
					onChange={ updated => {
						const values = updated ? [ ...props.selected, item.slug ] : without( props.selected, item.slug );

						props.onChange( values );
					} }
				/>
			</li>
		));
	} else {
		choices = [ <li><Spinner /></li> ]
	}

	return (
		<BaseControl label={ __( 'Tags', 'directorist' ) } className="directorist-gb-cb-list-control">
			<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
		</BaseControl>
	);
});

export const ListingControl = withSelect( select => {
	const args = {
		per_page: -1,
		orderby: 'title',
		order: 'asc'
	}
	
	return {
		items: select( 'core' ).getEntityRecords( 'postType', POST_TYPE, args )
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		choices = ( props.shouldRender ? sortItemsBySelected( props.items, props.selected, 'id' ) : props.items ).map( item => (
			<li key={ item.id }>
				<CheckboxControl
					label={ truncate( item.title.rendered, { length: 30 } ) }
					checked={ props.selected.includes( item.id ) }
					onChange={ updated => {
						const values = updated ? [ ...props.selected, item.id ] : without( props.selected, item.id );

						props.onChange( values );
					} }
				/>
			</li>
		));
	} else {
		choices = [ <li><Spinner /></li> ]
	}

	return (
		<BaseControl 
            label={ __( 'Listing Items', 'directorist' ) }
            help={ __( 'When manually selecting listing items make sure to deselect categories, tags and locations.', 'directorist' ) }
            className="directorist-gb-cb-list-control">
			<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
		</BaseControl>
	);
});

export const TypesControl = withSelect( select => {
	const args = {
		per_page: -1,
		order   : 'asc',
		orderby : 'name'
	}

	return {
		items: select('core').getEntityRecords( 'taxonomy', TYPE_TAX, args )
	}
})( props => {
	let choices = [],
		defaultDirChoices = [];

	if ( props.items ) {
		choices = ( props.shouldRender ? sortItemsBySelected( props.items, props.selected, 'slug' ) : props.items ).map( item => (
			<li key={ item.id }>
				<CheckboxControl
					label={ truncate( item.name, { length: 30 } ) }
					checked={ props.selected.includes( item.slug ) }
					onChange={ updated => {
						const values = updated ? [ ...props.selected, item.slug ] : without( props.selected, item.slug );

						props.onChange( values );
					} }
				/>
			</li>
		));
		
		if ( props.showDefault ) {
			defaultDirChoices = props.items.filter( item => props.selected.includes( item.slug ) ).map( item => {
				return {
					label: truncate( item.name, { length: 30 } ),
					value: item.slug
				}
			} );
		}
	} else {
		choices = [ <li><Spinner /></li> ]
	}

	return (
		<Fragment>
			<BaseControl label={ __( 'Directory Types', 'directorist' ) } className="directorist-gb-cb-list-control">
				<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
			</BaseControl>

			{ defaultDirChoices.length && props.showDefault ? <RadioControl
				label={ __( 'Select Default Directory', 'directorist' ) }
				selected={ props.defaultType }
				options={ defaultDirChoices }
				onChange={ props.onDefaultChange }
			/> : '' }
		</Fragment>
	);
} );

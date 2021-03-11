import { withSelect, select } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';
import { sortItemsBySelected } from './functions'
import { without, truncate } from 'lodash';

import {
	CheckboxControl,
	BaseControl
} from '@wordpress/components';

export const LocationControl = withSelect( select => {
	const args = {
		per_page: -1,
		order: 'asc',
		orderby: 'name'
	}

	return {
		items: select('core').getEntityRecords( 'taxonomy', 'at_biz_dir-location', args )
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
		items: select('core').getEntityRecords( 'taxonomy', 'at_biz_dir-category', args )
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
		items: select('core').getEntityRecords( 'taxonomy', 'at_biz_dir-tags', args )
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
		items: select( 'core' ).getEntityRecords( 'postType', 'at_biz_dir', args )
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

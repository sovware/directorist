import { withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';
import { sortItemsBySelected } from '../functions'

import {
	CheckboxControl,
	BaseControl
} from '@wordpress/components';

export const LocationControl = withSelect( select => {
	return {
		items: select('core').getEntityRecords('taxonomy', 'at_biz_dir-location')
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		props.items.forEach( item => {
			if ( props.selected.indexOf( item.slug ) !== -1 ) {
				choices.unshift(
					<li>
						<CheckboxControl
							label={ item.name }
							checked={ true }
							onChange={ value => props.onChange( value, item.slug ) }
						/>
					</li>
				);
			} else {
				choices.push(
					<li>
						<CheckboxControl
							label={ item.name }
							checked={ false }
							onChange={ value => props.onChange( value, item.slug ) }
						/>
					</li>
				);
			}
		});
	} else {
		choices.push( <li><Spinner /></li> );
	}
	
	return (
		<BaseControl label={ __( 'Locations', 'directorist' ) } className="directorist-gb-cb-list-control">
			<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
		</BaseControl>
	);
});

export const CategoryControl = withSelect( select => {
	return {
		items: select('core').getEntityRecords('taxonomy', 'at_biz_dir-category')
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		props.items.forEach( item => {
			if ( props.selected.indexOf( item.slug ) !== -1 ) {
				choices.unshift(
					<li>
						<CheckboxControl
							label={ item.name }
							checked={ true }
							onChange={ value => props.onChange( value, item.slug ) }
						/>
					</li>
				);
			} else {
				choices.push(
					<li>
						<CheckboxControl
							label={ item.name }
							checked={ false }
							onChange={ value => props.onChange( value, item.slug ) }
						/>
					</li>
				);
			}
		});
	} else {
		choices.push( <li><Spinner /></li> );
	}
	
	return (
		<BaseControl label={ __( 'Categories', 'directorist' ) } className="directorist-gb-cb-list-control">
			<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
		</BaseControl>
	);
});

export const TagsControl = withSelect( select => {
	return {
		items: select('core').getEntityRecords('taxonomy', 'at_biz_dir-tags')
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		props.items.forEach( item => {
			if ( props.selected.indexOf( item.slug ) !== -1 ) {
				choices.unshift(
					<li>
						<CheckboxControl
							label={ item.name }
							checked={ true }
							onChange={ value => props.onChange( value, item.slug ) }
						/>
					</li>
				);
			} else {
				choices.push(
					<li>
						<CheckboxControl
							label={ item.name }
							checked={ false }
							onChange={ value => props.onChange( value, item.slug ) }
						/>
					</li>
				);
			}
		});
	} else {
		choices.push( <li><Spinner /></li> );
	}
	
	return (
		<BaseControl label={ __( 'Tags', 'directorist' ) } className="directorist-gb-cb-list-control">
			<ul className="editor-post-taxonomies__hierarchical-terms-list">{ choices }</ul>
		</BaseControl>
	);
});

export const ListingControl = withSelect( select => {
	return {
		items: select( 'core' ).getEntityRecords( 'postType', 'at_biz_dir' )
	}
})( props => {
	let choices = [];

	if ( props.items ) {
		choices = props.items.map( item => (
			<li key={ item.id }>
				<CheckboxControl
					label={ `${item.title.rendered.slice( 0, 22 )}...` }
					checked={ props.selected.includes( item.id ) }
					onChange={ value => props.onChange( value, item.id ) }
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

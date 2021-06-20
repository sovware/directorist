import { withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { sortItemsBySelected, remapTaxTerms, remapPosts } from './functions'
import { without, truncate, isEmpty, uniqBy, pluck } from 'lodash';
import { Fragment, useState, useMemo } from '@wordpress/element';
import TokenMultiSelectControl from './vendors/token-multiselect-control';
import apiFetch from '@wordpress/api-fetch';

const {
	categoryTax: CATEGORY_TAX,
	locationTax: LOCATION_TAX,
	postType   : POST_TYPE,
	tagTax     : TAG_TAX,
	typeTax    : TYPE_TAX
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

const TaxonomyMultiSelectControl = withSelect( ( select, props ) => {
	const args = {
		per_page: 10,
		order   : 'asc',
		orderby : 'name'
	};

	if ( ! isEmpty( props.value ) ) {
		args.slug     = props.value;
		args.orderby  = 'include_slugs';
		args.per_page = props.value.length;
	}

	return {
		terms: select( 'core' ).getEntityRecords( 'taxonomy', props.taxonomy, args )
	}
})( props => {
	if ( isEmpty( props.terms ) ) {
		return <Spinner />
	}

	const defaultOptions          = remapTaxTerms( props.terms );
	const [ options, setOptions ] = useState( defaultOptions );
	const [ value, setValue ]     = useState( props.value );

	return (
		<TokenMultiSelectControl
			className="directorist-gb-multiselect"
			maxSuggestions={ 10 }
			label={ props.label }
			value={ value }
			options={ options }
			onChange={ value => {
				setValue( value );
				props.onChange( value );
			} }
			onInputChange={ term => {
				apiFetch( { path: `wp/v2/${props.taxonomy}?per_page=10&search=${term}` } )
				.then( ( results ) => {
					const serachedOptions = remapTaxTerms( results )
					setOptions( uniqBy( options.concat(...serachedOptions ), 'value' ) )
				} );
			} }
		/>
	);
} );

export const TagsTaxControl = props => (
	<TaxonomyMultiSelectControl {...props } taxonomy={ TAG_TAX } label={ __( 'Select Tags', 'directorist' ) } />
);

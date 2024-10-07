import { withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { sortItemsBySelected, remapTaxTerms, remapPosts, decodeHTML } from './functions'
import { without, truncate, isEmpty, uniqBy, isEqual } from 'lodash';
import { Fragment, Component } from '@wordpress/element';
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
					label={ truncate( decodeHTML( item.name ), { length: 30 } ) }
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
					label: truncate( decodeHTML( item.name ), { length: 30 } ),
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

class TermsMultiSelectComponent extends Component {
	constructor( props ) {
		super( props );

		this.state = {
			options: [],
			value: this.props.value,
			isLoading: true
		}
	}

	static getDerivedStateFromProps( props, state ) {
		if ( ! isEmpty( props.options ) || ! isEmpty( state.options ) ) {
			return {
				options: uniqBy( state.options.concat( props.options ), 'value' ),
				isLoading: false,
			}
		}
		return null;
	}

	render() {
		if ( this.state.isLoading ) {
			return (
				<BaseControl label={ this.props.label }>
					<Spinner />
				</BaseControl>
			);
		}

		return (
			<TokenMultiSelectControl
				maxSuggestions={ 10 }
				label={ this.props.label }
				value={ this.state.value }
				options={ this.state.options }
				onChange={ value => {
					this.setState( {
						value: value,
						isLoading: false
					} );

					this.props.onChange( value );
				} }
				onInputChange={ term => {
					apiFetch( { path: `wp/v2/${this.props.taxonomy}?per_page=10&search=${term}` } )
					.then( ( terms ) => {
						const options = remapTaxTerms( terms )
						this.setState( { options } )
					} );
				} }
			/>
		);
	}
}

const TermsMultiSelectControl = withSelect( ( select, props ) => {
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

	const terms = select( 'core' ).getEntityRecords( 'taxonomy', props.taxonomy, args )

	return {
		options: ( ! isEmpty( terms ) ) ? remapTaxTerms( terms ) : []
	}
})( TermsMultiSelectComponent );

export const TagsControl = props => (
	<TermsMultiSelectControl {...props } taxonomy={ TAG_TAX } label={ __( 'Select Tags', 'directorist' ) } />
);

export const CategoryControl = props => (
	<TermsMultiSelectControl {...props } taxonomy={ CATEGORY_TAX } label={ __( 'Select Categories', 'directorist' ) } />
);

export const LocationControl = props => (
	<TermsMultiSelectControl {...props } taxonomy={ LOCATION_TAX } label={ __( 'Select Locations', 'directorist' ) } />
);

class PostsMultiSelectComponent extends Component {
	constructor( props ) {
		super( props );

		this.state = {
			options: [],
			value: this.props.value,
			isLoading: true
		}
	}

	static getDerivedStateFromProps( props, state ) {
		if ( ! isEmpty( props.options ) || ! isEmpty( state.options ) ) {
			return {
				options: uniqBy( state.options.concat( props.options ), 'value' ),
				isLoading: false,
			}
		}
		return null;
	}

	render() {
		if ( this.state.isLoading ) {
			return (
				<BaseControl label={ this.props.label }>
					<Spinner />
				</BaseControl>
			);
		}

		return (
			<TokenMultiSelectControl
				maxSuggestions={ 10 }
				label={ this.props.label }
				value={ this.state.value }
				options={ this.state.options }
				onChange={ value => {
					this.setState( {
						value: value,
						isLoading: false
					} );

					this.props.onChange( value );
				} }
				onInputChange={ term => {
					apiFetch( { path: `wp/v2/${this.props.postType}?per_page=10&search=${term}` } )
					.then( ( posts ) => {
						const options = remapPosts( posts )
						this.setState( { options } )
					} );
				} }
			/>
		);
	}
}

const PostsMultiSelectControl = withSelect( ( select, props ) => {
	const args = {
		per_page: 10,
		order   : 'desc',
		orderby : 'date'
	};

	if ( ! isEmpty( props.value ) ) {
		args.include  = props.value;
		args.orderby  = 'include';
		args.per_page = props.value.length;
	}

	const posts =  select( 'core' ).getEntityRecords( 'postType', props.postType, args )

	return {
		options: ( ! isEmpty( posts ) ) ? remapPosts( posts ) : []
	}
})( PostsMultiSelectComponent );

export const ListingControl = props => (
	<PostsMultiSelectControl {...props } postType={ POST_TYPE } label={ __( 'Select Listings', 'directorist' ) } />
);

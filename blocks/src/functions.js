import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { truncate, unescape } from 'lodash';

export function getAttsForTransform( attributes = {} ) {
    let _atts = {};

    for ( const [key, value] of Object.entries( attributes ) ) {
        _atts[ key ] = {
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

    return _atts;
}

export function sortItemsBySelected( items = [], selected = [], key = 'id' ) {
    const isSelectedItem = item => selected.includes( item[ key ] );

    const itemsSlected = ( itemA, itemB ) => {
        const itemASelected = isSelectedItem( itemA );
        const itemBSelected = isSelectedItem( itemB );

        if ( itemASelected === itemBSelected ) {
            return 0;
        }

        if ( itemASelected && ! itemBSelected ) {
            return -1;
        }

        if ( ! itemASelected && itemBSelected ) {
            return 1;
        }

        return 0;
    };

    items.sort( itemsSlected );

    return items;
}

export function isMultiDirectoryEnabled() {
    return !!directoristBlockConfig.multiDirectoryEnabled;
}

export function getWithSharedAttributes( attributes = {} ) {
    attributes.isPreview = {
        type: 'boolen',
        default: false
    }

    attributes.query_type = {
        type: 'string',
        default: 'regular'
    }

    return attributes;
}

export function getPreview(name, isPreviewPopup = false) {
    return (
        <Fragment>
            <img
            style={{display: 'block', width: '100%', height: 'auto'}}
            className="directorist-block-preview"
            src={`${directoristBlockConfig.previewUrl}preview/${name}.svg`}
            alt={ __( 'Preview', 'directorist' ) } />
            { ! isPreviewPopup && <div style={{textAlign: 'center', fontSize: '12px', marginTop: '5px'}}><em>It's a placeholder. Please check the preview on frontend.</em></div> }
        </Fragment>
    );
}

export function remapTaxTerms( terms, ignorables = [] ) {
    return terms.map( term => {
        return {
            value: term.slug,
            label: truncate( decodeHTML( term.name ), { length: 30 } ),
        }
    } )
}

export function remapPosts( posts, ignorables = [] ) {
    return posts.map( post => {
        return {
            value: post.id,
            label: truncate( decodeHTML( post.title.rendered ), { length: 30 } ),
        }
    } )
}

export function decodeHTML( text ) {
    const textarea = document.createElement( 'textarea' );
    textarea.innerHTML = text;
    return textarea.textContent;
}
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

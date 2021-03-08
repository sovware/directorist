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

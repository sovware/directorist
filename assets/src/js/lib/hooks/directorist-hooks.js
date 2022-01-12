const directoristHooks = {
    filters: {},
    actions: {},

    applyFilter: function( tag, args ) {
      if ( typeof this.filters[tag] === 'function' ) {
        const filteredData = this.filters[tag]( args );

        if ( typeof args === typeof filteredData ) {
            return filteredData;
        }
      }

      return args;
    },

    addFilter: function( tag, callback ) {
      if ( typeof tag !== 'string'  ) {
        return;
      }

      if ( typeof callback !== 'function'  ) {
        return;
      }

      const sanitizedTag = tag.replace( ' ', '' );
      this.filters[sanitizedTag] = callback;
    },

    doAction: function( tag, args ) {
      if ( typeof this.actions[tag] === 'function' ) {
        this.actions[tag]( args );
      }
    },

    addAction: function( tag, callback ) {
      if ( typeof tag !== 'string'  ) {
        return;
      }

      if ( typeof callback !== 'function'  ) {
        return;
      }

      const sanitizedTag = tag.replace( ' ', '' );
      this.actions[sanitizedTag] = callback;
    }
};

export default directoristHooks;
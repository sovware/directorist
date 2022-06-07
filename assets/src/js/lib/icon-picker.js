const IconPicker = ( args ) => {
    return {
        value: '',
        iconType: 'solid',
        container: null,
        onSelect: null,
        icons: null,

        init: function () {
            this.container = ( typeof args.container !== 'undefined' ) ? args.container : this.container;
            this.onSelect = ( typeof args.onSelect !== 'undefined' ) ? args.onSelect : this.onSelect;
            this.icons = ( typeof args.icons !== 'undefined' ) ? args.icons : this.icons;
            this.value = ( typeof args.value === 'string' ) ? args.value : this.value;

            if ( ! this.container ) {
                return;
            }

            this.renderIcons();
            this.attachEvents();
        },

        renderIcons() {

            console.log( 'renderIcons' );

            let markup = '';

            for ( const iconGroupKey of Object.keys( this.icons ) ) {
                markup += `<div class="icons-group ${iconGroupKey}">`;
                markup += `<h4>${this.icons[ iconGroupKey ].label}</h4>`;
                markup += `<div class="icons-group-icons">`;

                for ( const icon of this.icons[ iconGroupKey ].icons  ) {
                    const fullIcon = this.getFullIcon( icon.key, iconGroupKey, this.iconType );
                    const buttonClass = ( this.value === fullIcon ) ? 'cptm-btn-primary' : 'cptm-btn-secondery';

                    markup += `
                        <button class="font-icon-btn cptm-btn ${buttonClass}" style="margin: 5px" data-group-key="${iconGroupKey}" data-icon-key="${icon.key}">
                            ${icon.key}
                        </button>
                    `;
                }

                markup += `</div>`;
                markup += `</div>`;
            }

            this.container.innerHTML = markup;
        },

        attachEvents() {
            const iconButtons = document.querySelectorAll( '.font-icon-btn' );
            const self = this;

            for ( const iconButton of iconButtons ) {

                iconButton.addEventListener( 'click', function( event ) {

                    const iconGroupKey = event.target.getAttribute( 'data-group-key' );
                    const iconKey = event.target.getAttribute( 'data-icon-key' );
                    const icon = self.getFullIcon( iconKey, iconGroupKey, self.iconType );

                    self.value = icon;

                    self.renderIcons();
                    self.attachEvents();

                    if ( typeof self.onSelect === 'function' ) {
                        self.onSelect( icon );
                    }

                });

            }
        },

        getFullIcon( iconKey, iconGroupKey, iconType ) {
            const prefix = this.icons[ iconGroupKey ].iconTypes[ iconType ].key;
            return `${prefix} ${iconKey}`;
        }
    }
};

export { IconPicker };
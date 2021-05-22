import Vue from "vue";

export default {
    watch: {
        theAvailableWidgets() {
            this.syncLayoutWithWidgets();
        }
    },

    methods: {
        syncLayoutWithWidgets() {
            let available_widgets_keys = Object.keys( this.theAvailableWidgets );
            let active_widgets_keys = Object.keys( this.active_widgets );

            if ( ! available_widgets_keys.length ) { return; }
            if ( ! active_widgets_keys.length ) { return; }
            if ( ! ( this.local_layout && typeof this.local_layout === 'object' ) ) { return; }

            // Find deprecated widgests
            let deprecated_widgests = {};
            for ( let widget_key of active_widgets_keys ) {
                if ( available_widgets_keys.includes( widget_key ) ) { continue; }

                deprecated_widgests[ widget_key ] = {
                    widget_key: this.active_widgets[ widget_key ].widget_key,
                    widget_name: this.active_widgets[ widget_key ].widget_name,
                }
            }

            const deprecated_widgests_keys = Object.keys( deprecated_widgests );
            if ( ! deprecated_widgests_keys.length ) { return; }

            console.log( this.local_layout );

            for ( let section_key in this.local_layout ) {
                const section = this.local_layout[ section_key ];
                if ( ! ( section && typeof section === 'object' ) ) { continue; }

                for ( let sub_section_key in section ) {
                    const sub_section = section[ sub_section_key ];

                    if ( ! ( sub_section && typeof sub_section === 'object' ) ) { continue; }
                    if ( ! ( sub_section.selectedWidgets && Array.isArray( sub_section.selectedWidgets ) ) ) { continue; }
                    if ( ! sub_section.selectedWidgets.length ) { continue; }

                    for ( let widget_key of sub_section.selectedWidgets ) {
                        if ( ! deprecated_widgests_keys.includes( widget_key ) ) { continue; }

                        let _index = sub_section.selectedWidgets.indexOf( widget_key );
                        this.local_layout[ section_key ][ sub_section_key ].selectedWidgets.splice( _index, 1 );

                        Vue.delete( this.active_widgets, widget_key );
                    }
                }
            }

        },
    },
}
import props from './input-field-props.js';
import helpers from '../helpers';
const axios = require('axios').default;

export default {
    name: 'export-data-field',
    mixins: [ props, helpers ],

    created() {
        if ( this.buttonLabel && this.buttonLabel.length ) {
            this.button_label = this.buttonLabel;
        }
    },

    data() {
        return {
            button_label: 'Export',
            isPreparingExportFile: false,
            validation_message: null,
        }
    },

    methods: {
        exportData() {
            if ( this.prepareExportFileFrom.length ) {
                this.prepareExportFile();
                return;
            }

            switch ( this.exportAs ) {
                case 'csv':
                    this.export_CSV();
                    break;

                case 'json':
                    this.export_JSON();
                    break;
            
                default:
                    this.export_CSV();
                    break;
            }
        },

        prepareExportFile() {
            let data = new FormData();
            data.append( 'action', this.prepareExportFileFrom );

            if ( this.isPreparingExportFile ) {
                console.log( 'Please wait...' );
                return;
            }

            const button_label_default = this.button_label;
            this.button_label = `<i class="fas fa-circle-notch fa-spin"></i> ${button_label_default}`;

            this.isPreparingExportFile = true;
            const self = this;

            axios.post( ajax_data.ajax_url, data ).then( function( response ) {
                console.log( {response} );

                self.button_label = button_label_default;
                self.isPreparingExportFile = false;

                if ( response?.data?.file_url ) {
                    self.downloadURI( self.exportFileName, response.data.file_url );
                }

            }).catch( function( error ) {
                console.log( {error} );

                self.button_label = button_label_default;
                self.isPreparingExportFile = false;
            });

        },

        downloadURI( name, uri ) {
            var link = document.createElement("a");
            link.download = name;
            link.href = uri;
            document.body.appendChild(link);

            link.click();
            
            document.body.removeChild(link);
        },

        export_CSV() {
            if ( ! Array.isArray( this.data ) ) { return; }

            let dataStr = "data:text/csv;charset=utf-8,";

            let tr_count  = 0;
            let delimeter = ',';

            let table = this.justifyTable( this.data );

            for ( let tr of table ) {
                if ( ! tr || typeof tr !== 'object' ) { continue; }

                // Header Row
                let header_row_array = [];
                if ( 0 === tr_count ) {
                    for ( let td in tr ) {
                        header_row_array.push( `"${td}"` );
                    }

                    let header_row = header_row_array.join( delimeter );
                    dataStr += header_row + "\r\n";
                }
                
                // Body Row
                let body_row_array = [];
                for ( let td in tr ) {
                    let data = ( typeof tr[ td ] === 'object' ) ? '' : tr[ td ];
                    body_row_array.push( `"${data}"` );
                }

                let body_row = body_row_array.join( delimeter );
                dataStr += body_row + "\r\n";

                tr_count++;
            }

            const dataUri = encodeURI( dataStr );
            const exportFileDefaultName = this.exportFileName + '.csv';

            let linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        },


        export_JSON() {
            let dataStr = JSON.stringify( this.data );
            let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

            let exportFileDefaultName = this.exportFileName + '.json';

            let linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        },

        justifyTable( table ) {

            if ( ! Array.isArray( table ) ) { return table; }
            if ( ! table.length ) { return table; }

            let tr_lengths = [];
            table.forEach( ( item, index ) => {
                tr_lengths.push( Object.keys( item ).length );
            });

            let top_tr = tr_lengths.indexOf( Math.max( ...tr_lengths ) );
            const modal_tr = table[ top_tr ];

            let justify_table = [];
            table.forEach( ( item, index ) => {
                let tr = {};
                for ( let key in modal_tr ) {
                    tr[ key ] = ( item[ key ] ) ? item[ key ] : '';
                }
                justify_table.push( tr );
            });

            return justify_table;
        }
    }
}
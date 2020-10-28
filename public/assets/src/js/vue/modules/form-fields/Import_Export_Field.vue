<template>
<div class="cptm-form-group">
    <label>{{ label }}</label>
    
    <div class="cptm-btn-group">
        <button type="button" class="cptm-btn cptm-btn-secondery" @click="exportJSON()">
            <span class="fa fa-upload"></span>
            Export
        </button>

        <label for="import" class="cptm-label-btn cptm-btn cptm-btn-primary ">
            <span class="fa fa-download"></span>
            Import
        </label>
        
        <input class="cptm-d-none" type="file" id="import" accept=".json" @change="importJSON( $event )">
    </div>

    <div class="cptm-form-group-feedback" v-if="validation_messages.length">
        <div class="cptm-form-alert" :class="'cptm-' + validation.type" v-for="( validation, validation_key ) in validation_messages" :key="validation_key">
            {{ validation.message }}
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from '../../mixins/helpers';

export default {
    name: 'import-export-field',
    mixins: [ helpers ],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        label: {
            type: String,
            required: false,
            default: '',
        },
        validation: {
            type: Array,
            required: false,
        },
    },

    computed: {
        ...mapState([
            'fields', 'layouts', 'config'
        ])
    },

    data() {
        return {
            validation_messages: [
                // { type: 'success', message: 'Test' }
            ]
        }
    },

    methods: {
        exportJSON() {
            let data = {
                fields: this.fields,
            };

            let dataStr = JSON.stringify( data );
            console.log( { data, dataStr } );
            let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

            let exportFileDefaultName = 'directory.json';

            let linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        },

        importJSON( event ) {
            console.log( 'importJSON' );
            let reader = new FileReader();
            let self = this;

            reader.onload = function( event ) {
                let json = JSON.parse( event.target.result );
                // console.log( { json } );
                
                if ( json && typeof json === 'object' && ! Array.isArray( json ) ) {
                    self.serveJSON( json );
                }
            };

            reader.readAsText( event.target.files[0] );
        },

        serveJSON( json ) {
            console.log( { json } );

            let accepted_data = {
                fields: {
                    commit: 'updateFields',
                },
            };

            for ( let data in json ) {
                if ( ! Object.keys( accepted_data ).includes( data ) )  { continue; }
                this.$store.commit( accepted_data[ data ].commit, json[ data ] );
            }

            this.validation_messages.push({ type: 'success', message: 'The file has been loaded successfully' });
        }
    }
}
</script>

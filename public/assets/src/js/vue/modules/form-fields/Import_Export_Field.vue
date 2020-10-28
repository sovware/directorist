<template>
<div class="cptm-form-group" :class="formGroupClass">
    <label v-if="( 'hidden' !== input_type && label.length )" :for="name">{{ label }}</label>
    
    <div class="cptm-btn-group">
        <button type="button" class="cptm-btn cptm-btn-secondery" @click="exportJSON()">
            <span class="fa fa-upload"></span>
            Export
        </button>

        <button type="button" class="cptm-btn cptm-btn-primary" @click="importJSON()">
            <span class="fa fa-download"></span>
            Import
        </button>
    </div>

    <div class="cptm-form-group-feedback" v-if="validation_messages.length">
        <div class="cptm-form-alert" :class="'cptm-' + validation.type" v-for="( validation, validation_key ) in validationMessages" :key="validation_key">
            {{ validation.message }}
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'import-export-field',
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
            export_link: '#',
            export_name: '',

            validation_messages: [
                // { type: 'success', message: 'Test' }
            ]
        }
    },

    methods: {
        exportJSON() {
            let data = {
                fields: this.fields,
                layouts: this.layouts,
                config: this.config,
            };

            console.log( 'exportJSON' );

            let dataStr = JSON.stringify( data );
            let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

            let exportFileDefaultName = 'directory.json';

            let linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        },

        importJSON() {
            console.log( 'importJSON' );
        },
    }
}
</script>

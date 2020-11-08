<template>
<div class="cptm-form-group">
    <label>{{ label }}</label>
    
    <div class="cptm-btn-group">
        <button type="button" class="cptm-btn cptm-btn-secondery" @click="exportJSON()">
            <span class="fa fa-upload"></span>
            Export
        </button>
    </div>
</div>
</template>

<script>
import { mapGetters } from 'vuex';
import helpers from '../../mixins/helpers';

export default {
    name: 'export-field',
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

    methods: {
        ...mapGetters([
            'getFieldsValue'
        ]),

        exportJSON() {
            // console.log( this.getFieldsValue() );
            let dataStr = JSON.stringify( this.getFieldsValue() );
            let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

            let exportFileDefaultName = 'directory.json';

            let linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        },
    }
}
</script>

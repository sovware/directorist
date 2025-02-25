<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( 'hidden' !== input_type && label.length )">
            <component :is="labelType">{{ label }}</component>
        </label>

        <input type="text" :value="value" @input="$emit( 'update', $event.target.value )" />
        
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
        
        <p>
            Test Data: {{  testData  }}
            <button type="button" @click="updateTestData()">Update Test Data</button>
        </p>
        
    </div>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'repeater-field',
    mixins: [ props, feild_helper ],

    data() {
        return {
            testData: 'Test Old'
        }
    },

    methods: {
        updateTestData() {
            this.testData = 'Test Updated';
        }
    }

}
</script>

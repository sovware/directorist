<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <div class="cptm-form-group-row">
            <div class="cptm-form-group-col">
                <label v-if="( 'hidden' !== input_type && label.length )" :for="name">{{ label }}</label>
            </div>

            <div class="cptm-form-group-col">
                <input class="cptm-form-control" :class="formControlClass" v-if="( typeof value !== 'object' ) ? true : false" :type="input_type" :value="value" :placeholder="placeholder" @input="$emit('update', $event.target.value)">
                <input v-if="( typeof value === 'object' ) ? true : false" type="hidden" :value="JSON.stringify( value )">
                <p v-html="description" class="cptm-form-group-info"></p>

                <div class="cptm-form-group-feedback" v-if="validationMessages">
                    <div class="cptm-form-alert" :class="'cptm-' + validationMessages.type">
                        {{ validationMessages.message }}
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</template>

<script>
import text_feild from './../../../../mixins/form-fields/text-field';

export default {
    name: 'text-field-theme-butterfly',
    mixins: [ text_feild ],
}
</script>
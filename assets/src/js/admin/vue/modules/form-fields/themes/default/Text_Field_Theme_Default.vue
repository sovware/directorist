<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( 'hidden' !== input_type && label.length )">
            <component :is="labelType">{{ label }}</component>
        </label>
        
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
        
        <input class="cptm-form-control" :class="formControlClass" v-if="( typeof value !== 'object' ) ? true : false" :type="input_type" :value="value" :placeholder="placeholder" @input="$emit('update', $event.target.value)">
        <input v-if="( typeof value === 'object' ) ? true : false" type="hidden" :value="JSON.stringify( value )">
            
        <form-field-validatior 
            :section-id="sectionId"
            :field-id="fieldId"
            :root="root"
            :value="value" 
            :rules="rules" 
            v-model="validationLog" 
            @validate="$emit( 'validate', $event )"
        />
    </div>
</template>

<script>
import text_feild from './../../../../mixins/form-fields/text-field';

export default {
    name: 'text-field-theme-default',
    mixins: [ text_feild ],
}
</script>
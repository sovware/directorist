<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( 'hidden' !== input_type && label.length )" :for="name">{{ label }}</label>
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
        
        <textarea name="" id="" :cols="cols" :rows="rows" :placeholder="placeholder" class="cptm-form-control" v-model="local_value"></textarea>

        <form-field-validatior 
            :section-id="sectionId"
            :field-id="fieldId"
            :root="root"
            :value="local_value" 
            :rules="rules" 
            v-model="validationLog" 
            @validate="$emit( 'validate', $event )"
        />
    </div>
</template>

<script>
import textarea_feild from './../../../../mixins/form-fields/textarea-field';

export default {
    name: 'textarea-field-theme-default',
    mixins: [ textarea_feild ],
}
</script>
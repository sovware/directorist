<template>
    <div class="cptm-form-group cptm-form-group-button-example" :class="formGroupClass">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="( label.length )">
                    <component :is="labelType">{{ label }}</component>
                </label>
                
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>
            
            <div class="atbdp-col atbdp-col-8">
                <button 
                    class="directorist-btn"
                    :class="buttonClass"
                >
                    {{ buttonLabel }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import button_example_field from '../../../../mixins/form-fields/button-example-field';

export default {
    name: 'button-example-field-theme-butterfly',
    mixins: [ button_example_field ],
}
</script>
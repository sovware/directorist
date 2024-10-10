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
                    :style="buttonStyles"
                    @mouseover="hovered = true"
                    @mouseleave="hovered = false"
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

    data() {
        return {
            hovered: false, // Track hover state
        };
    },

    computed: {
        // Get button type from store
        buttonType() {
            return this.$store.state.fields.button_type.value;
        },

        // Get the colors based on the button type
        buttonStyles() {
            const { 
                primary_color, 
                primary_hover_color, 
                back_primary_color, 
                secondary_color, 
                secondary_hover_color, 
                back_secondary_color 
            } = this.$store.state.fields;

            if (this.buttonType === 'solid_primary') {
                return {
                    color: this.hovered ? primary_hover_color.value : primary_color.value,
                    backgroundColor: back_primary_color.value,
                };
            } else if (this.buttonType === 'solid_secondary') {
                return {
                    color: this.hovered ? secondary_hover_color.value : secondary_color.value,
                    backgroundColor: back_secondary_color.value,
                };
            } else {
                return {}; // Default or other cases
            }
        }
    },
}
</script>
<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( label.length )" :for="name">{{ label }}</label>
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <input type="button" v-if="! generateShortcode" class="cptm-btn cptm-generate-shortcode-button" value="Generate Shortcode" @click="generate">
        <div v-if="generateShortcode" class="cptm-shortcode" ref="shortcode" @click="copyToClip">{{ shortcode }}</div>
        <div v-if="successMsg.length" class="cptm-info-text cptm-info-success">{{ successMsg }}</div>
            
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
import Button_Card_Widget from '../../../../../../../../../../../../../../../directoria/app/public/wp-content/plugins/directorist/assets/src/js/vue/modules/card-widgets/Button_Card_Widget.vue';
import text_feild from '../../../../mixins/form-fields/shortcode-field';

export default {
  components: { Button_Card_Widget },
    name: 'shortcode-field-theme-default',
    mixins: [ text_feild ],
}
</script>
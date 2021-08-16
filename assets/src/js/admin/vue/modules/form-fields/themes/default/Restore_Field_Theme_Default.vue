<template>
    <div class="cptm-form-group">
        <label v-if="( label.length )">
            <component :is="labelType">{{ label }}</component>
        </label>
        
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <button type="button" class="cptm-btn cptm-btn-secondery" @click="restore()">
            <span class="fa fa-download"></span>
            {{ buttonLabel }}
        </button>

        <div class="cptm-form-group-feedback" v-if="validation_message">
            <div class="cptm-form-alert" :class="'cptm-' + validation_message.type">
                {{ validation_message.message }}
            </div>
        </div>
    </div>
</template>

<script>
import restore_field from '../../../../mixins/form-fields/restore-field';

export default {
    name: 'restore-field-theme-default',
    mixins: [ restore_field ],
}
</script>
<template>
    <div class="cptm-form-group">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="label.length">{{ label }}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>
            
            <div class="atbdp-col atbdp-col-8">
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
        </div>
    </div>
</template>

<script>
import restore_feild from '../../../../mixins/form-fields/restore-field';

export default {
    name: 'restore-field-theme-butterfly',
    mixins: [ restore_feild ],
}
</script>
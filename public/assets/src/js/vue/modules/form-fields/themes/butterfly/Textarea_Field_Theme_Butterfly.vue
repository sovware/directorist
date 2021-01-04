<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="( 'hidden' !== input_type && label.length )" :for="name">{{ label }}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>
            
            <div class="atbdp-col atbdp-col-8">
                <textarea name="" id="" :cols="cols" :rows="rows" :placeholder="placeholder" class="cptm-form-control" v-model="local_value"></textarea>

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
import textarea_feild from './../../../../mixins/form-fields/textarea-field';

export default {
    name: 'textarea-field-theme-butterfly',
    mixins: [ textarea_feild ],
}
</script>
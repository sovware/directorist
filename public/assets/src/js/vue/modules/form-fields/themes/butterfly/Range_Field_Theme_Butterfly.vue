<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="( 'hidden' !== type && label.length )" :for="name">{{ label }}</label>
            </div>

            <div class="atbdp-col atbdp-col-4">
                <div class="cptm-form-range-wrap">
                    <div class="cptm-form-range-bar">
                        <input 
                            type="range" 
                            :id="fieldId"
                            :step="step" 
                            :min="min" 
                            :max="max" 
                            :name="name" 
                            v-model="range_value"
                        >
                    </div>

                    <div class="cptm-form-range-output">
                        <span class="cptm-form-range-output-text">{{ range_value }}</span>
                    </div>
                </div>

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
import range_feild from '../../../../mixins/form-fields/range-field';

export default {
    name: 'range-field-theme-butterfly',
    mixins: [ range_feild ],
}
</script>
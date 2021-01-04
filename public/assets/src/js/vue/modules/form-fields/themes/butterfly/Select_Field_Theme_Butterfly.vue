<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="label.length">{{label}}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>

            <div class="atbdp-col atbdp-col-8">
                <select @change="update_value( $event.target.value )" :value="local_value" class="cptm-form-control">
                    <option v-if="showDefaultOption && default_option" :value="default_option.value">{{ default_option.label }}</option>
                    <template v-for="( option, option_key ) in theOptions">
                        <template v-if="option.group && option.options">
                            <optgroup :label="option.group" :key="option_key">
                                <option 
                                    v-for="( sub_option, sub_option_key ) in option.options" 
                                    :key="sub_option_key" 
                                    :value="sub_option.value"
                                    v-html="sub_option.label"
                                >
                                </option>
                            </optgroup>
                        </template>

                        <template v-else>
                            <option :key="option_key" :value="option.value">{{ option.label }}</option>
                        </template>
                    </template>
                </select>
                
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
import select_field from '../../../../mixins/form-fields/select-field';

export default {
    name: 'select-field-theme-butterfly',
    mixins: [ select_field ],
}
</script>
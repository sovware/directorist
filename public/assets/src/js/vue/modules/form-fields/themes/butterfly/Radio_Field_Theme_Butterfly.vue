<template>
    <div class="cptm-form-group">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label>{{label}}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>

            <div class="atbdp-col atbdp-col-8">
                <div class="cptm-radio-area">
                    <div class="cptm-radio-item" v-for="( option, option_index ) in theOptions" :key="option_index">
                        <input type="radio" class="cptm-radio" 
                            :id="getOptionID( option, option_index )"
                            :name="name"
                            :value="( typeof option.value !== 'undefined' ) ? option.value : ''"
                            v-model="local_value"
                        >
                        <label :for="getOptionID( option, option_index )">
                            {{ option.label }}
                        </label>
                    </div>
                </div>

                <p class="cptm-info-text" v-if="! theOptions.length">{{ infoTextForNoOption }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import radio_feild from './../../../../mixins/form-fields/radio-field';

export default {
    name: 'radio-field-theme-butterfly',
    mixins: [ radio_feild ],
}
</script>
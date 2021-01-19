<template>
    <div class="cptm-form-group cptm-theme-butterfly">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label>{{label}}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>

            <div class="atbdp-col atbdp-col-8">
                <div class="cptm-checkbox-area">
                    <div class="cptm-checkbox-item" v-for="( option, option_index ) in theOptions" :key="option_index">
                        <input type="checkbox" class="cptm-checkbox"
                            :id="getOptionID( option, option_index )"
                            :value="( typeof option.value !== 'undefined' ) ? option.value : ''"
                            v-model="local_value"
                        >

                        <label :for="getOptionID( option, option_index )" class="cptm-checkbox-ui"></label>
                        <label :for="getOptionID( option, option_index )" v-html="option.label"></label>
                    </div>
                </div>

                <p class="cptm-info-text" v-if="! theOptions.length" >{{ infoTextForNoOption }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import checkbox_feild from './../../../../mixins/form-fields/checkbox-field';

export default {
    name: 'checkbox-field-theme-butterfly',
    mixins: [ checkbox_feild ],
}
</script>
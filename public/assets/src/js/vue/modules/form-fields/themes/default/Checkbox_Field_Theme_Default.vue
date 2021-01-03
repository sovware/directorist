<template>
    <div class="cptm-form-group">
        <label>{{label}}</label>

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

        <p class="cptm-info-text" v-if="! theOptions.length">{{ infoTextForNoOption }}</p>
    </div>
</template>

<script>
import checkbox_feild from './../../../../mixins/form-fields/checkbox-field';

export default {
    name: 'checkbox-field-theme-default',
    mixins: [ checkbox_feild ],
}
</script>
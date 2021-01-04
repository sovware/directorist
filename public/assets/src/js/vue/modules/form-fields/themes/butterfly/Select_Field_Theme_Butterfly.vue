<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="label.length">{{label}}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>

            <div class="atbdp-col atbdp-col-8">
                <div class="directorist_dropdown">
                    <a href="#" class="directorist_dropdown-toggle" @click.prevent="toggleTheOptionModal()">
                        <span class="directorist_dropdown-toggle__text">{{  theCurrentOptionLabel }}</span>
                    </a>
                    
                    <div class="directorist_dropdown-option" v-if="theOptions" :class="{ ['--show']: show_option_modal }">
                        <ul>
                            <li v-for="( option, option_key ) in theOptions" :key="option_key">
                                <a href="#" 
                                    v-html="( option.label ) ? option.label : ''"
                                    @click.prevent="updateOption( option.value )"
                                >
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <select @change="update_value( $event.target.value )" :value="local_value" class="cptm-d-none">
                    <option v-if="showDefaultOption && default_option" :value="default_option.value">{{ default_option.label }}</option>
                    <template v-for="( option, option_key ) in theOptions">
                        <option :key="option_key" :value="option.value">{{ option.label }}</option>
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
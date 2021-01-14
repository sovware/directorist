<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( 'hidden' !== type && label.length )" :for="name">{{ label }}</label>
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <div class="cptm-form-range-wrap">
            <div class="cptm-form-range-bar">
                <div class="directorist_slider-range">
                    <span class="directorist_range-bar">
                        <span class="directorist_range-fill" :style="rangeFillStyle"></span>
                    </span>

                    <input 
                        type="range" 
                        class="directorist_slider-input" 
                        :id="fieldId"
                        :step="theStep" 
                        :min="theMin" 
                        :max="theMax" 
                        :name="name" 
                        v-model="range_value"
                    >
                </div>
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
</template>

<script>
<template>
    <div class="cptm-form-group">
        <div class="cptm-input-toggle-wrap">
            <label :for="name">{{ label }}</label>
            <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

            <span class="cptm-input-toggle" :class="toggleClass" @click="toggleValue()"></span>
            <input type="checkbox" :id="name" :name="name" style="display: none;" :checked="local_value">
        </div>
    </div>
</template>

<script>
import toggle_feild from './../../../../mixins/form-fields/toggle-field';

export default {
    name: 'toggle-field-theme-default',
    mixins: [ toggle_feild ],
}
</script>
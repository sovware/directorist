<template>
    <div class="cptm-form-group">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label :for="name">{{ label }}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>
            
            <div class="atbdp-col atbdp-col-8">
                <span class="cptm-input-toggle" :class="toggleClass" @click="toggleValue()"></span>
                <input type="checkbox" :id="name" :name="name" style="display: none;" :checked="local_value">
            </div>
        </div>
    </div>
</template>

<script>
import toggle_feild from './../../../../mixins/form-fields/toggle-field';

export default {
    name: 'toggle-field-theme-butterfly',
    mixins: [ toggle_feild ],
}
</script>
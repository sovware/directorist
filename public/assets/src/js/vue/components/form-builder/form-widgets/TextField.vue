<template>
    <div class="form-group">
        <label v-if="options.label" :for="options.name">{{ options.label }}</label>
        <input 
            type="text" 
            :id="options.name" 
            :v-model="options.value"
            :placeholder="options.placeholder"
            class="form-control"
        >
    </div>
</template>

<script>
export default {
    name: 'text-field',
    props: ['options'],
}
</script>
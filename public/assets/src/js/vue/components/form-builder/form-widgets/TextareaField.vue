<template>
    <div class="form-group">
        <label v-if="options.label" :for="options.name">{{ options.label }}</label>
        <textarea
            :id="options.name"
            :placeholder="options.placeholder"
            v-model="options.value"
            class="form-control">
        </textarea>
            
    </div>
</template>

<script>
export default {
    name: 'textarea-field',
    props: ['options'],
}
</script>
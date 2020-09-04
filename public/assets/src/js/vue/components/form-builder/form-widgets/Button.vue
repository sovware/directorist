<template>
    <button :type="options.type" :class="btn_class">
        {{ options.text }}
    </button>
</template>

<script>
export default {
    name: 'text-field',
    props: ['options'],

    computed: {
        btn_class() {
            let class_name = 'btn';
            const btn_size = {
                default: '',
                full: ' btn-block',
                small: ' btn-sm',
            };

            if ( this.options.style ) {
                class_name = `${class_name} btn-${this.options.style}`;
            }

            if ( this.options.size ) {
                const size = ( btn_size[ this.options.size ] ) ? btn_size[ this.options.size ] : '';
                class_name = `${class_name}${size}`;
            }

            return class_name;
        }
    }
}
</script>
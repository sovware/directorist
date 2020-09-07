<template>
    <div class="">
        <template v-for="( field, index ) in postMetaFields">
            <input :key="index" type="hidden" :name="index" :value="field">
        </template>
    </div>    
</template>

<script>
import { mapState } from 'vuex';
import { mapGetters } from 'vuex';

export default {
    name: 'hidden-input',
    created() {
        // console.log( this.postMetaFields );
    },
    computed: {
        ...mapGetters({
            postMetaFields: 'getPostMetaFields'
        }),
    },
}
</script>
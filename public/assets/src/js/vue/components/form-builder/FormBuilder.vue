<template>
    <div class="row">
        <div class="col-md-4">
            <div class="widget-sidebar">
                Widget List
            </div>
        </div>
        <div class="col-md-8">
            <div class="widget-list">
                <form-reader :form="form"/>
            </div>
        </div>
    </div>
</template>

<script>
import widget_list from './form-widgets/widget-list';
import form_reader from './FormReader.vue';

export default {
    name: 'form_builder',
    mixins: [ widget_list ],
    components: {
        'form-reader': form_reader
    },
    props: ['form'],

    data() {
        return {
            
        }
    }
}
</script>
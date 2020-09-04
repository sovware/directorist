<template>
    <form v-if="form.rows.length" :action="form.action" :method="form.method">
        <div class="row" v-for="( row, row_index ) in form.rows" :key="row_index">
            <template v-if="form.rows[row_index]">
                <div v-for="( col, col_index) in row" :key="col_index" :class="'col-md-' + col.options.col_size">
                    <template v-if="form.rows[row_index][col_index].widgets.length">
                        <template v-for="( widget, widget_index ) in col.widgets">
                            <component 
                                :is="getWidget( widget.name )" 
                                :key="widget_index"
                                :options="widget.options"
                            />
                        </template>
                    </template>
                </div>
            </template>
        </div>
    </form>
</template>

<script>
import widget_list from './form-widgets/widget-list';

export default {
    name: 'form_reader',
    props: ['form'],
    mixins: [ widget_list ],

    methods: {
        getWidget( widget_name ) {
            if ( this.widgets[widget_name] ) {
                return this.widgets[widget_name];
            }

            return null;
        }
    }
}
</script>
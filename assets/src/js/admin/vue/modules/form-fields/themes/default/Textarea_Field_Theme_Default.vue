<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( label.length )">
            <component :is="labelType">{{ label }}</component>
        </label>
        
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <div v-if="editor" :id="editorID"></div>
        
        <textarea v-else name="" :cols="cols" :rows="rows" :placeholder="placeholder" class="cptm-form-control" v-model="local_value"></textarea>

        <form-field-validatior 
            :section-id="sectionId"
            :field-id="fieldId"
            :root="root"
            :value="local_value" 
            :rules="rules" 
            v-model="validationLog" 
            @validate="$emit( 'validate', $event )"
        />
    </div>
</template>

<script>
import textarea_feild from './../../../../mixins/form-fields/textarea-field';

export default {
    name: 'textarea-field-theme-default',
    mixins: [ textarea_feild ],
    props: {
        editor: {
            required: false,
            default: '',
        },
        editorID: {
            required: false,
            default: '',
        },
        fieldId: {
            required: false,
            default: '',
        },
        value: {
            required: false,
            default: '',
        },
    },

    mounted() {
        let editorID = this.editorID;
        let value = this.value;
        
        tinymce.init({
            selector: `#${editorID}`,
            plugins: 'link',
            toolbar: 'undo redo | formatselect | bold italic | link',
            menubar: false,
            branding: false,
            init_instance_callback: (editor) => {
                // Set the initial content using the init_instance_callback
                editor.setContent(value);
            },
        });

        // Save the editor instance for later use
        this.editorInstance = tinymce.get(editorID);
    },

}
</script>
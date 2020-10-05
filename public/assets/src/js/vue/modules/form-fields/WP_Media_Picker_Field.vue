<template>
<div class="cptm-form-group">
    <div class="cptm-thumbnail">
        <div class="cptm-thumbnail-img-wrap" v-if="thumbnailSrc.length">
            <img :src="thumbnailSrc" class="cptm-thumbnail-img" width="100%" height="auto"/>

            <span v-if="hasThumbnail" class="cptm-thumbnail-action action-trash" @click="deleteThumbnail()">
                <i class="uil uil-trash-alt"></i>
            </span>
        </div>

        <span v-if="! thumbnailSrc.length" class="cptm-thumbnail-placeholder">
            <span class="cptm-thumbnail-placeholder-icon">
                <i class="uil uil-image"></i>
            </span>
        </span>
    </div>

    <input type="button" @click.prevent="openMediaPicker" class="cptm-btn cptm-btn-primary" :value="buttonLabel">
</div>
</template>

<script>
export default {
    name: 'image-field',
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        type: {
            type: String,
            required: false,
            default: 'text',
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
        value: {
            type: [ Object ],
            required: false,
            default: '',
        },
        defaultImg: {
            required: false,
        },
        selectButtonLabel: {
            required: false,
            type: String,
            default: 'Select',
        },
        changeButtonLabel: {
            required: false,
            type: String,
            default: 'Change',
        },
        validation: {
            type: Array,
            required: false,
        },
    },

    computed: {
        theThumbnail() {
            return {
                id: this.thumbnail_id,
                url: this.thumbnailSrc,
            }
        },

        hasThumbnail() {
            if ( this.thumbnail_id && this.thumbnail_src.length ) {
                return true;
            }

            return false;
        },

        thumbnailSrc() {
            if ( this.thumbnail_src === '' ) {
                return this.defaultImg;
            }

            return this.thumbnail_src;
        },

        buttonLabel() {
            if ( this.hasThumbnail ) {
                return this.changeButtonLabel;
            }

            return this.selectButtonLabel;
        }
    },
    
    watch: {
        theThumbnail() {
            this.$emit( 'update', this.theThumbnail );
        }
    },

    mounted() {
        this.setup();
    },

    data() {
        return {
            file_frame: null,
            thumbnail_id: null,
            thumbnail_src: '',
        }
    },

    methods: {
        setup() {
            if ( this.value && typeof this.value.id !== 'undefined' && this.value.url !== 'undefined' ) {
                this.thumbnail_id = this.value.id;
                this.thumbnail_src = this.value.url;
            }

            this.createTheMediaFrame();
            this.$emit( 'update', this.theThumbnail );
        },

        createTheMediaFrame() {
            let self = this;

            // Create the media frame.
            this.file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            this.file_frame.on( 'select', function() {
                let attachment = self.file_frame.state().get('selection').first().toJSON();

                self.thumbnail_id = attachment.id;
                self.thumbnail_src = attachment.url;
            });
        },

        openMediaPicker() {
            let self = this;

            if ( this.file_frame ) {
                this.file_frame.open();
                return;
            }

            this.createTheMediaFrame();
        },

        deleteThumbnail() {
            this.thumbnail_id = '';
            this.thumbnail_src = '';
        }
    }
}
</script>

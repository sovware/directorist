import validator from './../validation';
import props from './input-field-props.js';

export default {
    mixins: [ props, validator ],

    model: {
        prop: 'value',
        event: 'input'
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

    created() {
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
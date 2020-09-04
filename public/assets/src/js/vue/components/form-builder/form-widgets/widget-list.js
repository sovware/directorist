import text from './TextField.vue';
import textarea from './TextareaField.vue';
import button from './Button.vue';

export default {
    data() {
        return {
            widgets: {
                text,
                textarea,
                button,
            }
        }
    }
}
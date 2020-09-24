import text from './../modules/form-fields/Text_Field.vue';
import toggle from './../modules/form-fields/Toggle_Field.vue';
import select from './../modules/form-fields/Select_Field.vue';
import image_picker from './../modules/form-fields/Image_Field.vue';
import button from './../modules/form-fields/Button_Field.vue';
import form_builder from './../modules/Form_Builder.vue';
import card_builder from './../modules/Card_Builder.vue';

export default {
    text,
    number: text,
    password: text,
    hidden: text,
    date: text,
    toggle,
    select,
    image_picker,
    button,
    form_builder,
    card_builder,
}
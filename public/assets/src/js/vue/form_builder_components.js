import Vue from 'vue';
import contactForm from './components/ContactForm.vue';
import ManageForm from './components/ManageForm.vue';

// Contact Form
const contact_form_elm = document.querySelectorAll('.contact-form');
contact_form_elm.forEach(element => {
    new Vue({
        el: element,
        components: {
            'contact-form': contactForm
        }
    });
});

// Manage Form
const manage_form_elm = document.querySelectorAll('.manage-form');
manage_form_elm.forEach(element => {
    new Vue({
        el: element,
        components: {
            'manage-form': ManageForm
        }
    });
});

; (function ( $ ) {
    this.ATBDP_Form_Handler = function (args) {
        this.option = {
            el: $('.atbdp-form'),
            alertClass: 'atbdp-form-alert',
            message: {
                success: 'The Form has been submitted successfully',
                error: 'Something went wrong, please try again',
                loading: 'Please wait...',
            },
        };

        this.allFormData = {};

        // init
        this.init = function () {
            var self = this;
            var form = this.option.el;

            if (!form.length) { return; }

            $.each(form, function (index) {
                var form_key = 'atbdp-form-' + (index + 1);
                $(form[index]).attr('data-form-key', form_key);

                self.allFormData[form_key] = {
                    state: {}, formData: new FormData()
                };

                self.handleForm(form[index], form_key);
            });
        };

        // handleForm
        this.handleForm = function (form, form_key) {
            var self = this;

            $(form).on('submit', function (e) {
                e.preventDefault();

                var form = e.target;
                var is_processesing = $(form).attr('data-is-processesing');

                if (is_processesing && is_processesing === 'true') {
                    return;
                }

                $(form).attr('data-is-processesing', true);
                self.allFormData[form_key].state.isProcessesing = true;

                var form_action = $(form).attr('action');

                var fields = self.getFields( form_key );
                if ( ! fields ) { return; }

                self.allFormData[form_key].formData.append('action', form_action);

                $.each(fields, function (index) {
                    self.handleField(fields[index], form_key);
                });

                self.sendRequest(form_key);
            });
        };

        // handleField
        this.handleField = function (field, form_key) {
            var name = $(field).attr('name');
            var value = $(field).val();

            this.allFormData[form_key].formData.append(name, value);
        };

        // getFields
        this.getFields = function (form_key) {
            var form = this.getForm(form_key);
            if (!form) { return null; }

            var fields = $(form).find('.atbdp-form-field');
            if (!fields.length) { return null; }

            return fields;
        };

        // sendRequest
        this.sendRequest = function (form_key) {
            var self = this;
            var form_data = this.allFormData[form_key].formData;
            this.allFormData[form_key].state.isSubmitted = true;

            var handler = ('handler' in this.allFormData[form_key]) ? self.allFormData[form_key].handler : false;
            var has_success_handler = (handler && 'success' in handler) ? true : false;
            var has_error_handler = (handler && 'error' in handler) ? true : false;

            this.initLoading(form_key);

            $.ajax({
                url: atbdp_public_data.ajaxurl,
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function( response ){
                    if ( has_success_handler ) {
                        handler.success(response, self);
                        return;
                    }
    
                    self.onSuccess(response, form_key);
                },
                error: function () {
                    if ( has_error_handler ) {
                        handler.error(error, self);
                        return;
                    }
    
                    self.onError(response, form_key);
                }
            });
        };

        this.onSuccess = function (response, form_key) {
            if (this.isJson(response)) {
                response = JSON.parse(response);
            }

            var success_msg = this.option.message.success;
            var error_msg = this.option.message.error;

            var response_is_json = (response && typeof response === 'object');
            var response_is_string = (response && typeof response === 'string');

            if (!response_is_json) {
                var msg = (response_is_string) ? response : success_msg;

                this.showAlert(form_key, 'success', msg);
                this.resetForm(form_key);

                return;
            }

            success_msg = ('message' in response) ? response.message : success_msg;
            error_msg = ('message' in response) ? response.message : error_msg;

            // If has error response
            if (('error' in response && response.error) ||
                ('success' in response && !response.success)) {

                this.showAlert(form_key, 'danger', error_msg);
                this.resetLoading(form_key);

                return;
            }

            // If has success response 
            if (('error' in response && !response.error) ||
                ('success' in response && response.success)) {

                this.showAlert(form_key, 'success', success_msg);
                this.resetForm(form_key);
            }
        };

        // onError
        this.onError = function (response, form_key) {
            var response_is_string = (response && typeof response === 'string');
            var msg = (response_is_string) ? response : this.option.message.error;

            this.showAlert(form_key, 'danger', msg);
        };

        // initLoading
        this.initLoading = function (form_key) {
            this.showAlert(form_key, 'info', this.option.message.loading);
        };

        // resetLoading
        this.resetLoading = function (form_key) {
            var form = this.getForm(form_key);
            if (!form) { return; }

            $(form).attr('data-is-processesing', true);
            this.allFormData[form_key].state.isProcessesing = true;
        };

        // resetForm
        this.resetForm = function (form_key) {
            this.resetLoading(form_key);

            var fields = this.getFields(form_key);

            $.each(fields, function (index) {

                $(fields[index]).val('');
            });
        };

        // showAlert
        this.showAlert = function (form_key, type, message) {
            var form = this.getForm(form_key);
            if (!form) { return; }

            var alert_class = '.' + this.option.alertClass;
            var alert = $(form).find(alert_class);

            var msg = '<div class="alert alert-' + type + '">' + message + '</div>';
            alert.html(msg);
        };

        // getFormByKey
        this.getFormByKey = function (form_key) {
            return $('*[data-form-key="' + form_key + '"]');
        };

        // getFormByID
        this.getFormByID = function (form_id) {
            return $('*[data-form-id="' + form_id + '"]');
        };

        // getForm
        this.getForm = function (form_key_id) {
            var form = this.getFormByKey(form_key_id);

            if (!form.length) {
                form = this.getFormByID(form_key_id);
            }

            if (!form.length) { return null; }

            return form;
        };

        // addCustomHandler
        this.addCustomHandler = function (handler) {
            if (!handler || typeof handler !== 'object') {
                return;
            }

            var form = this.getForm(handler.formID);
            if (!form) { return; }

            var form_key = $(form).attr('data-form-key');

            this.allFormData[form_key].handler = handler;
        };

        // isJson
        this.isJson = function (str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        this.init();
    }
})( jQuery );
;(function($) {
    'use strict';

    class Attachments_Preview {
        constructor( form ) {
            this.$form = $( form );
            this.$input = this.$form.find( '.directorist-review-images' );
            this.$preview = this.$form.find( '.directorist-review-img-gallery' );

            this.init();
        }

        init() {
            const self = this;

            this.$input.on( 'change', function() {
                self.showPreview( this );
            } );

            this.$form.on( 'click', '.directorist-btn-delete', function(e) {
                e.preventDefault();
                $( this ).parent().remove();
            } );
        }

        // deleteFromFileList(fileField, index) {
        //     let fileBuffer = Array.from(fileField.files);
        //     fileBuffer.splice(index, 1);

        //     /** Code from: https://stackoverflow.com/a/47172409/8145428 */
        //     // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
        //     // specs compliant (as of March 2018 only Chrome)
        //     const dataTransfer = new ClipboardEvent('').clipboardData || new DataTransfer();

        //     for (let file of fileBuffer) {
        //         dataTransfer.items.add(file);
        //     }
        //     fileField.files = dataTransfer.files;
        // }

        // addToFileList(fileField, index) {
        //     let fileBuffer = Array.from(fileField.files);
        //     fileBuffer.splice(index, 1);

        //     /** Code from: https://stackoverflow.com/a/47172409/8145428 */
        //     // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
        //     // specs compliant (as of March 2018 only Chrome)
        //     const dataTransfer = new ClipboardEvent('').clipboardData || new DataTransfer();

        //     for (let file of fileBuffer) {
        //         dataTransfer.items.add(file);
        //     }
        //     fileField.files = dataTransfer.files;
        // }

        showPreview(input) {
            this.$preview.html('');

            for (let i = 0, len = input.files.length; i < len; i++) {
                const fileReader = new FileReader();
                let file = input.files[i];

                if (!file.type.startsWith('image/')) {
                    continue;
                }

                fileReader.onload = event => {
                    const html = `
                    <div class="directorist-review-gallery-preview preview-image">
                        <img src="${event.target.result}" alt="Directorist Review Preview">
                        <a href="#" class="directorist-btn-delete"><i class="la la-trash"></i></a>
                    </div>
                    `;

                    this.$preview.append(html);
                }

                fileReader.readAsDataURL(file);
            }
        }
    }

    class ActivityStorage {

        add( commentId, activity ) {
            if ( this.has( commentId, activity ) ) {
                return false;
            }
            const activities = this.getActivities();

            if ( typeof activities[ commentId ] === 'undefined' ) {
                activities[ commentId ] = {};
            }

            if ( typeof activities[ commentId ][ activity ] === 'undefined' || ! activities[ commentId ][ activity ] ) {
                activities[commentId][activity] = 1;
            }

            this.saveActivities( activities );

            return true;
        }

        has( commentId, activity ) {
            const activities = this.getActivities();

            if (typeof activities[commentId] === 'undefined') {
                return false;
            }

            if (typeof activities[commentId][activity] === 'undefined' || !activities[commentId][activity]) {
                return false;
            }

            return true;
        }

        saveActivities(activities) {
            this.getStorage().setItem('directorist', JSON.stringify({ activities }));
        }

        getActivities() {
            const storage = this.getStorage();
            let data = {
                activities: {}
            };

            if (storage.getItem('directorist')) {
                data = JSON.parse(storage.getItem('directorist'));
                if (typeof data['activities'] === 'undefined') {
                    data.activities = {}
                }
            } else {
                storage.setItem('directorist', JSON.stringify(data));
            }

            return data.activities;
        }

        hasStorage(name) {
            try {
              const storage = window[name]
              storage.setItem('hello___test__key', '1')
              storage.removeItem('hello___test__key')
              return true
            } catch (e) {
              return false
            }
        }

        getStorage() {
            let storage = null;

            if (this.hasStorage('localStorage')) {
                storage = window.localStorage
            } else if (this.hasStorage('sessionStorage')) {
                storage = window.sessionStorage
            }
            return storage;
        }
    }

    class Comment_Activity {
        constructor( storage ) {
            this.selector = '[data-directorist-activity]';
            this.$wrap    = $('.directorist-review-content__reviews');
            this.storage  = storage;

            this.init();
        }

        init() {
            this.$wrap.on(
                'click.onDirectoristActivity',
                this.selector,
                this.callback.bind(this)
            );
        }

        callback(event) {
            event.preventDefault();

            const $target = $(event.currentTarget);
            const activityProp = $target.data('directorist-activity');

            if (!activityProp) {
                return;
            }

            const [commentId, activity] = activityProp.split(':');

            if (!commentId || !activity) {
                return;
            }

            if (this.storage.has(commentId, activity)) {
                $target.addClass('processing').attr('disabled', true);
                return;
            }

            if ($target.hasClass('processing')) {
                return;
            } else {
                $target.addClass('processing').attr('disabled', true);

                if (activity === 'helpful' || activity === 'unhelpful') {
                    $target.find('span').html($target.data('count') + 1);
                    $target.data('count', $target.data('count') + 1);
                }
            }

            this.timeout && clearTimeout(this.timeout);

            this.send(commentId, activity)
                .done(response => {
                    const $comment = $('#div-comment-'+commentId);
                    let type = 'warning';

                    if (response.success) {
                        $target.removeClass('processing').removeAttr('disabled', true);
                        type = 'success';
                    }

                    $comment.find('.directorist-alert').remove();
                    $comment.prepend(this.getAlert(type).html(response.data));

                    this.timeout = setTimeout(() => {
                        $comment.find('.directorist-alert').slideUp('medium');
                        clearTimeout(this.timeout);
                    }, 3000);

                    this.storage.add(commentId, activity);
                });
        }

        getAlert(type) {
            return $('<div />', {
                class: 'directorist-alert directorist-alert-' + type
            });
        }

        send(commentId, activity) {
            return $.post(
                directorist.ajaxUrl,
                {
                    action: directorist.action,
                    nonce: directorist.nonce,
                    comment_id: commentId,
                    activity: activity
                }
            );
        }
    }

    class ReplyFormObserver {
        constructor() {
            const node = document.querySelector('.commentlist');

            if (node) {
                this.observe(node);
            }
        }

        observe(node) {
            const config = {
                childList: true,
                subtree: true
            };
            const observer = new MutationObserver(this.callback);
            observer.observe(node, config);
        }

        callback(mutationsList, observer) {
            for (const mutation of mutationsList) {
                if (mutation.removedNodes) {
                    mutation.target.classList.remove('directorist-form-added');

                    for(const node of mutation.removedNodes) {
                        if (node.id && node.id === 'respond') {
                            const criteria = node.querySelector('.directorist-review-criteria');
                            if (criteria) {
                                criteria.style.display = '';
                            }

                            const ratings = node.querySelectorAll('.directorist-review-criteria-select');
                            for (const rating of ratings) {
                                rating.removeAttribute('disabled');
                            }
                        }
                    }
                }

                const form = mutation.target.querySelector('#commentform');
                if (form) {
                    mutation.target.classList.add('directorist-form-added');
                    const criteria = form.querySelector('.directorist-review-criteria');
                    if (criteria) {
                        criteria.style.display = 'none';
                    }

                    const ratings = form.querySelectorAll('.directorist-review-criteria-select');
                    for (const rating of ratings) {
                        rating.setAttribute('disabled', 'disabled');
                    }

                    const alert = form.querySelector('.directorist-alert');
                    if (alert) {
                        alert.style.display = 'none';
                    }
                }
            }
        };
    }

    class Ajax_Comment {

        constructor() {
            this.init();
        }

        init() {
            $( document ).on('submit', '#commentform', this.onSubmit );
        }

        static getErrorMsg($dom) {
            if ($dom.find('p').length) {
                return $dom.find('p').text();
            }
            return $dom.text();
        }

        static showError(form, $dom) {
            if (form.find('.directorist-alert').length) {
                form.find('.directorist-alert').remove();
            }
            const $error = $('<div />', {class: 'directorist-alert directorist-alert-danger'}).html(Ajax_Comment.getErrorMsg($dom));
            form.prepend($error)
        }

        onSubmit( event ) {
            event.preventDefault();

            const form = $( "#commentform" );
            const ori_btn_val = form.find( "[type='submit']" ).val();

            $( document ).trigger( 'directorist_reviews_before_submit', form );

            const do_comment = $.ajax({
                url        : form.attr('action'),
                type       : 'POST',
                contentType: false,
                cache      : false,
                processData: false,
                data       : new FormData(form[0])
            });

            $( "#comment" ).prop( "disabled", true );

            form.find( "[type='submit']" ).prop( "disabled", true ).val( 'loading' );

            do_comment.success(
                function ( data, status, request ) {

                    var body = $( "<div></div>" );
                    body.append( data );
                    var comment_section = ".directorist-review-container";
                    var comments = body.find( comment_section );

                    const errorMsg = body.find( '.wp-die-message' );
                    if (errorMsg.length > 0) {
                        Ajax_Comment.showError(form, errorMsg);

                        $( document ).trigger( 'directorist_reviews_update_failed' );

                        return;
                    }

                    // if ( comments.length < 1 ) {
                    //     comment_section = '.commentlist';
                    //     comments = body.find( comment_section );
                    // }

                    var commentslists = comments.find( ".commentlist li" );

                    var new_comment_id = false;

                    // catch the new comment id by comparing to old dom.
                    commentslists.each(
                        function ( index ) {
                            var _this = $( commentslists[ index ] );
                            if ( $( "#" + _this.attr( "id" ) ).length == 0 ) {
                                new_comment_id = _this.attr( "id" );
                            }
                        }
                    );

                    $( comment_section ).replaceWith( comments );

                    $( document ).trigger( 'directorist_reviews_updated', data );

                    var commentTop = $( "#" + new_comment_id ).offset().top;

                    // if ( $( 'body' ).hasClass( 'sticky-header' ) ) {
                    //     commentTop = $( "#" + new_comment_id ).offset().top - $( '#masthead' ).height();
                    // }

                    if ( $( 'body' ).hasClass( 'admin-bar' ) ) {
                        commentTop = commentTop - $( '#wpadminbar' ).height();
                    }

                    // scroll to comment
                    if ( new_comment_id ) {
                        $( "body, html" ).animate(
                            {
                                scrollTop: commentTop
                            },
                            600
                        );
                    }
                }
            );

            do_comment.fail(
                function ( data ) {
                    var body = $( "<div></div>" );
                    body.append( data.responseText );
                    body.find( "style,meta,title,a" ).remove();

                    Ajax_Comment.showError(form, body.find( '.wp-die-message' ));
                    // console.log(body.find( '.wp-die-message' ).);

                    // if ( typeof bb_vue_loader == 'object' &&
                    //     typeof bb_vue_loader.common == 'object' &&
                    //     typeof bb_vue_loader.common.showSnackbar != 'undefined' ) {
                    //     bb_vue_loader.common.showSnackbar( body )
                    // } else {
                    //     alert( body );
                    // }

                    $( document ).trigger( 'directorist_reviews_update_failed' );
                }
            );

            do_comment.always(
                function () {
                    $( "#comment" ).prop( "disabled", false );
                    $( "#commentform" ).find( "[type='submit']" ).prop( "disabled", false ).val( ori_btn_val );
                }
            );

            $( document ).trigger( 'directorist_reviews_after_submit', form );
        }
    }

    class Advanced_Review {
        constructor() {
            this.form = document.querySelector( '#commentform' );

            if (!this.form) {
                return;
            }

            this.setFormEncoding();

            this.init();
        }

        init() {
            this.setupComponents();

            $( document ).on( 'directorist_reviews_updated', function() {
                $(".directorist-stars").barrating({
                    theme: 'fontawesome-stars'
                });

                $('.directorist-review-criteria-select').barrating({
                    theme: 'fontawesome-stars'
                });
            } );

            $( document ).on( 'click', 'a[href="#respond"]', this.onWriteReivewClick );
        }

        onWriteReivewClick(event) {
            event.preventDefault();

            var respondTop = $( '#respond' ).offset().top;

            if ( $( 'body' ).hasClass( 'admin-bar' ) ) {
                respondTop = respondTop - $( '#wpadminbar' ).height();
            }

            $( "body, html" ).animate(
                {
                    scrollTop: respondTop
                },
                600
            );
        }

        setupComponents() {
            this.preview = new Attachments_Preview( this.form );
            new Comment_Activity(new ActivityStorage());
            new ReplyFormObserver();
            new Ajax_Comment();
        }

        setFormEncoding() {
            this.form.encoding = 'multipart/form-data';
        }
    }

    const advanced_review = new Advanced_Review();

}(jQuery));
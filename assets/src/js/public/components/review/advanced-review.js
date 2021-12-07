;(function($) {
    'use strict';

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
            this.$wrap    = $(document.body);
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

            const $comment = $('#div-comment-'+commentId);

            if (this.storage.has(commentId, activity)) {
                $comment.prepend(this.getAlert('info').html('Already reported!'));
                $target.addClass('processing').attr('disabled', true);

                this.timeout = setTimeout(() => {
                    $comment.find('.directorist-alert').slideUp('medium');
                    clearTimeout(this.timeout);
                }, 3000);

                return;
            }

            if ($target.hasClass('processing')) {
                return;
            }

            $target.addClass('processing').attr('disabled', true);

            this.timeout && clearTimeout(this.timeout);

            this.send(commentId, activity)
                .done(response => {
                    let type = 'warning';

                    if (response.success) {
                        $target.removeClass('processing').removeAttr('disabled', true);
                        type = 'success';
                        this.storage.add(commentId, activity);
                    }

                    $comment.find('.directorist-alert').remove();
                    $comment.prepend(this.getAlert(type).html(response.data));

                    this.timeout = setTimeout(() => {
                        $comment.find('.directorist-alert').slideUp('medium');
                        clearTimeout(this.timeout);
                    }, 3000);
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
            this.init();

            $( document ).on( 'directorist_reviews_updated', () => this.init() );
        }

        init() {
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

                    for (const node of mutation.removedNodes) {
                        if (node.id && node.id === 'respond') {
                            const criteria = node.querySelector('.directorist-review-criteria');
                            if (criteria) {
                                criteria.style.display = '';
                            }

                            const ratings = node.querySelectorAll('.directorist-review-criteria-select');
                            for (const rating of ratings) {
                                rating.removeAttribute('disabled');
                            }

                            node.querySelector('#submit').innerHTML = 'Submit review';
                            node.querySelector('#comment').setAttribute('placeholder', 'Leave a review' );
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

                    form.querySelector('#submit').innerHTML = 'Submit comment';
                    form.querySelector('#comment').setAttribute('placeholder', 'Leave your comment' );
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
            this.$doc = $(document);

            this.setupComponents();
            this.addEventListeners();
            this.setFormEncodingAttribute();
        }

        addEventListeners() {
            this.$doc.on( 'directorist_reviews_updated', () => {
                $('.directorist-stars, .directorist-review-criteria-select').barrating({
                    theme: 'fontawesome-stars'
                });

                this.setFormEncodingAttribute();
            } );

            this.$doc.on( 'click', 'a[href="#respond"]', this.onWriteReivewClick );
        }

        onWriteReivewClick(event) {
            event.preventDefault();

            var respondTop = $( '#respond' ).offset().top;

            if ( $( 'body' ).hasClass( 'admin-bar' ) ) {
                respondTop = respondTop - $( '#wpadminbar' ).height();
            }

            $( 'body, html' ).animate(
                {
                    scrollTop: respondTop
                },
                600
            );
        }

        setupComponents() {
            new Comment_Activity(new ActivityStorage());
            new ReplyFormObserver();
            new Ajax_Comment();
        }

        setFormEncodingAttribute() {
            const form = document.querySelector( '#commentform' );
            if ( form ) {
                form.encoding = 'multipart/form-data';
            }
        }
    }

    const advanced_review = new Advanced_Review();
}(jQuery));
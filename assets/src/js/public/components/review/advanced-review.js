window.addEventListener('DOMContentLoaded', () => {
    ;(function ($) {
        'use strict';
        class ReplyFormObserver {
            constructor() {
                this.init();
                $(document).on('directorist_review_updated', () => this.init());
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
                    const target = mutation.target;
                    if (mutation.removedNodes) {
                        target.classList.remove('directorist-form-added');
                        for (const node of mutation.removedNodes) {
                            if (!node.id || node.id !== 'respond') {
                                continue;
                            }
                            const criteria = node.querySelector('.directorist-review-criteria');
                            if (criteria) {
                                criteria.style.display = '';
                            }
                            const ratings = node.querySelectorAll('.directorist-review-criteria-select');
                            for (const rating of ratings) {
                                rating.removeAttribute('disabled');
                            }
                            node.querySelector('#submit').innerHTML = 'Submit Review';
                            node.querySelector('#comment').setAttribute('placeholder', 'Leave a review');
                            //console.log(node.querySelector('#comment'))
                        }
                    }

                    const form = target.querySelector('#commentform');
                    if (form) {
                        target.classList.add('directorist-form-added');
                        const isReview = target.classList.contains('review');
                        const isEditing = target.classList.contains('directorist-form-editing');

                        if (!isReview || (isReview && !isEditing)) {
                            const criteria = form.querySelector('.directorist-review-criteria');
                            if (criteria) {
                                criteria.style.display = 'none';
                            }
                            const ratings = form.querySelectorAll('.directorist-review-criteria-select');
                            for (const rating of ratings) {
                                rating.setAttribute('disabled', 'disabled');
                            }
                        }

                        const alert = form.querySelector('.directorist-alert');
                        if (alert) {
                            alert.style.display = 'none';
                        }

                        form.querySelector('#submit').innerHTML = 'Submit Reply';
                        form.querySelector('#comment').setAttribute('placeholder', 'Leave your reply');
                    }
                }
            };
        }

        class CommentEditHandler {
            constructor() {
                this.init();
            }

            init() {
                $(document).on('submit', '#directorist-form-comment-edit', this.onSubmit);
            }

            static showError($form, msg) {
                $form.find('.directorist-alert').remove();
                $form.prepend(msg)
            }

            onSubmit(event) {
                event.preventDefault();
                const $form = $(event.target);
                const originalButtonLabel = $form.find('[type="submit"]').val();
                $(document).trigger('directorist_review_before_submit', $form);
                const updateComment = $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData($form[0])
                });

                $form.find('#comment').prop('disabled', true);
                $form.find('[type="submit"]').prop('disabled', true).val('loading');
                const commentID = $form.find('input[name="comment_id"]').val();
                const $wrap = $('#div-comment-' + commentID);

                $wrap.addClass('directorist-comment-edit-request');

                updateComment.done((data, status, request) => {
                    if (typeof data !== 'string' && !data.success) {
                        $wrap.removeClass('directorist-comment-edit-request');
                        CommentEditHandler.showError($form, data.data.html);
                        return;
                    }

                    let body = $('<div></div>');
                    body.append(data);
                    let comment_section = '.directorist-review-container';
                    let comments = body.find(comment_section);

                    $(comment_section).replaceWith(comments);
                    $(document).trigger('directorist_review_updated', data);

                    let commentTop = $("#comment-" + commentID).offset().top;
                    if ($('body').hasClass('admin-bar')) {
                        commentTop = commentTop - $('#wpadminbar').height();
                    }

                    // scroll to comment
                    if (commentID) {
                        $("body, html").animate({
                                scrollTop: commentTop
                            },
                            600
                        );
                    }
                });

                updateComment.fail(data => {
                    CommentEditHandler.showError($form, data.responseText);
                });

                updateComment.always(() => {
                    $form.find('#comment').prop('disabled', false);
                    $form.find('[type="submit"]').prop('disabled', false).val(originalButtonLabel);
                });

                $(document).trigger('directorist_review_after_submit', $form);
            }
        }
        class CommentAddReplyHandler {
            constructor() {
                this.init();
            }

            init() {
                var t = setTimeout(function() {
                    if ($('.directorist-review-container').length) {
                        $(document).off('submit', '#commentform');
                    }
                    clearTimeout(t);
                }, 2000);

                $(document).off('submit', '.directorist-review-container #commentform');

                $(document).on('submit', '.directorist-review-container #commentform', this.onSubmit);
            }

            static getErrorMsg($dom) {
                if ($dom.find('p').length) {
                    $dom = $dom.find('p');
                }

                let words = $dom.text().split(':');
                if (words.length > 1) {
                    words.shift();
                }

                return words.join(' ').trim();
            }

            static showError(form, $dom) {
                if (form.find('.directorist-alert').length) {
                    form.find('.directorist-alert').remove();
                }
                const $error = $('<div />', {
                    class: 'directorist-alert directorist-alert-danger'
                }).html(CommentAddReplyHandler.getErrorMsg($dom));
                form.prepend($error)
            }

            onSubmit(event) {
                event.preventDefault();
                const form = $('.directorist-review-container #commentform');
                const originalButtonLabel = form.find('[type="submit"]').val();
                $(document).trigger('directorist_review_before_submit', form);
                const do_comment = $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(form[0])
                });

                $('#comment').prop('disabled', true);
                form.find('[type="submit"]').prop('disabled', true).val('loading');

                do_comment.done((data, status, request) => {
                    var body = $('<div></div>');
                    body.append(data);
                    var comment_section = '.directorist-review-container';
                    var comments = body.find(comment_section);

                    const errorMsg = body.find('.wp-die-message');
                    if (errorMsg.length > 0) {
                        CommentAddReplyHandler.showError(form, errorMsg);

                        $(document).trigger('directorist_review_update_failed');

                        return;
                    }

                    $(comment_section).replaceWith(comments);

                    $(document).trigger('directorist_review_updated', data);

                    let newComment = comments.find('.commentlist li:first-child');
                    let newCommentId = newComment.attr('id');

                    // // catch the new comment id by comparing to old dom.
                    // commentsLists.each(
                    //     function ( index ) {
                    //         var _this = $( commentsLists[ index ] );
                    //         if ( $( '#' + _this.attr( 'id' ) ).length == 0 ) {
                    //             newCommentId = _this.attr( 'id' );
                    //         }
                    //     }
                    // );

                    // console.log(newComment, newCommentId)

                    var commentTop = $("#" + newCommentId).offset().top;

                    if ($('body').hasClass('admin-bar')) {
                        commentTop = commentTop - $('#wpadminbar').height();
                    }

                    // scroll to comment
                    if (newCommentId) {
                        $('body, html').animate({scrollTop: commentTop}, 600);
                    }
                });

                do_comment.fail(data => {
                    var body = $('<div></div>');
                    body.append(data.responseText);

                    console.log(data);

                    CommentAddReplyHandler.showError(form, body.find('.wp-die-message'));

                    $(document).trigger('directorist_review_update_failed');

                    if (data.status === 403 || data.status === 401) {
                        $(document).off('submit', '.directorist-review-container #commentform', this.onSubmit);
                        $('#comment').prop('disabled', false);
                        form.find('[type="submit"]').prop('disabled', false).click();
                    }
                });

                do_comment.always(() => {
                    $('#comment').prop('disabled', false);
                    $('#commentform').find('[type="submit"]').prop('disabled', false).val(originalButtonLabel);
                });

                $(document).trigger('directorist_review_after_submit', form);
            }
        }

        class CommentsManager {
            constructor() {
                this.$doc = $(document);
                this.setupComponents();
                this.addEventListeners();
            }

            initStarRating() {
                $('.directorist-review-criteria-select').barrating({
                    theme: 'fontawesome-stars'
                });
            }

            cancelOthersEditMode(currentCommentId) {
                $('.directorist-comment-editing').each(function (index, comment) {
                    const $cancelButton = $(comment).find('.directorist-js-cancel-comment-edit');

                    if ($cancelButton.data('commentid') != currentCommentId) {
                        $cancelButton.click();
                    }
                });
            }

            cancelReplyMode() {
                const replyLink = document.querySelector('.directorist-review-content #cancel-comment-reply-link');
                replyLink && replyLink.click();
            }

            addEventListeners() {
                const self = this;

                this.$doc.on('directorist_review_updated', (event) => {
                    this.initStarRating();
                });

                this.$doc.on('directorist_comment_edit_form_loaded', (event) => {
                    this.initStarRating();
                });

                this.$doc.on('click', 'a[href="#respond"]', (event) => {
                    // First cancle the reply form then scroll to review form. Order matters.
                    this.cancelReplyMode();
                    this.onWriteReivewClick(event);
                });

                this.$doc.on('click', '.directorist-js-edit-comment', function (event) {
                    event.preventDefault();

                    const $target = $(event.target);
                    const $wrap = $target.parents('#div-comment-' + $target.data('commentid'));

                    $wrap.addClass('directorist-comment-edit-request');

                    $.ajax({
                        url: $target.attr('href'),
                        data: {
                            post_id: $target.data('postid'),
                            comment_id: $target.data('commentid')
                        },
                        setContent: false,
                        method: 'GET',
                        reload: 'strict',
                        success: function (response) {
                            $target
                                .parents('#div-comment-' + $target.data('commentid'))
                                .find('.directorist-review-single__contents-wrap').append(response.data.html);

                            $wrap
                                .removeClass('directorist-comment-edit-request')
                                .addClass('directorist-comment-editing');

                            self.cancelOthersEditMode($target.data('commentid'));
                            self.cancelReplyMode();

                            const $editForm = $('#directorist-form-comment-edit');
                            $editForm.find('textarea').focus();

                            self.$doc.trigger('directorist_comment_edit_form_loaded', $target.data('commentid'));
                        },
                    });
                });

                this.$doc.on('click', '.directorist-js-cancel-comment-edit', (event) => {
                    event.preventDefault();
                    const $target = $(event.target);
                    const $wrap = $target.parents('#div-comment-' + $target.data('commentid'));
                    $wrap
                        .removeClass(['directorist-comment-edit-request', 'directorist-comment-editing'])
                        .find('form')
                        .remove();
                });
            }

            onWriteReivewClick(event) {
                event.preventDefault();
                let scrollTop = $('#respond').offset().top;
                if ($('body').hasClass('admin-bar')) {
                    scrollTop = scrollTop - $('#wpadminbar').height();
                }
                $('body, html').animate({
                    scrollTop
                }, 600);
            }

            setupComponents() {
                new ReplyFormObserver();
                new CommentAddReplyHandler();
                new CommentEditHandler();
            }
        }

        const commentsManager = new CommentsManager();
    }(jQuery));
});
jQuery(document).ready(function($) {
        $('[data-slug="directorist"]').after('<tr class="directorist-extensions"></tr>');
        $('.directorist-extensions').append(
                $(
                        '<td colspan="4"><div class="ext-all-wrapper"><table><tbody class="de-list"></tbody></table></div></td>'
                )
        );
        const tbody = $('.directorist-extensions').find('.de-list');
        const extWrapper = $('.directorist-extensions').find('.ext-all-wrapper');
        $(extWrapper).append(
                '<div class="ext-more"><a href="" class="ext-more-link">Click to view directorist all modules</a></div>'
        );
        const moreLink = $('.directorist-extensions').find('.ext-more-link');
        $(moreLink).hide();

        $(tbody).append($('#the-list tr[data-slug^="directorist-"]'));

        if ($(extWrapper).innerHeight() > 250) {
                $(extWrapper).addClass('ext-height-fix');
                $(moreLink).show();
                $(extWrapper).css('padding-bottom', '60px');
        }
        $(moreLink).on('click', function(e) {
                e.preventDefault();
                if ($(extWrapper).hasClass('ext-height-fix')) {
                        $(extWrapper)
                                .animate({ height: '100%' }, 'fast')
                                .removeClass('ext-height-fix');
                        $(this).html('Click to collapse');
                } else {
                        $(extWrapper)
                                .animate({ height: '250px' }, 'fast')
                                .addClass('ext-height-fix');
                        setTimeout(() => {
                                $(this).html('Click to view directorist all modules');
                        }, 1000);
                }
        });

        if ($(tbody).html() === '') {
                $('.directorist-extensions').hide();
        }
});

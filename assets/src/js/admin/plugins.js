jQuery(document).ready(function($) {
        const update = $( '#directorist-update' );
        const main_div = $('[data-slug="directorist"]');
        const extensions_area = update.length ? update : main_div;
        extensions_area.after('<tr class="directorist-extensions"></tr>');
    $('.directorist-extensions').append(
            $(
                    '<td colspan="4"><div class="ext-all-wrapper"><input type="checkbox" class="select_all"> All Extensions<table class="atbdp_extensions"><tbody class="de-list"></tbody></table></div></td>'
            )
    );

    const tbody = $('.directorist-extensions').find('.de-list');
    const extWrapper = $('.directorist-extensions').find('.ext-all-wrapper');
    $(extWrapper).append(
            '<div class="ext-more"><a href="" class="ext-more-link">Click to view directorist all extensions</a></div>'
    );
    const moreLink = $('.directorist-extensions').find('.ext-more-link');
    $(moreLink).hide();

    $(tbody).append($('#the-list tr[data-slug^="directorist-"]'));

    $("body").on( 'click', '.select_all', function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
    });

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
                            $(this).html('Click to view directorist all extensions');
                    }, 1000);
            }
    });

    if ($(tbody).html() === '') {
            $('.directorist-extensions').hide();
    }
});
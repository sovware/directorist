import { directoristModalAlert } from "../directorist-modal-alert";
;(function ($) {

	// Dashboard Listing Ajax
	function directorist_dashboard_listing_ajax($activeTab,paged=1,search='',task='',taskdata='') {
		var tab = $activeTab.data('tab');
		$.ajax({
			url: atbdp_public_data.ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				'action': 'directorist_dashboard_listing_tab',
				'tab': tab,
				'paged': paged,
				'search': search,
				'task': task,
				'taskdata': taskdata,
			},
			beforeSend: function () {
				$('#directorist-dashboard-preloader').show();
			},
			success: function success(response) {
				$('.directorist-dashboard-listings-tbody').html(response.data.content);
				$('.directorist-dashboard-pagination').html(response.data.pagination);
				$('.directorist-dashboard-listing-nav-js a').removeClass('directorist-tab__nav__active');
				$activeTab.addClass('directorist-tab__nav__active');
				$('#directorist-dashboard-mylistings-js').data('paged',paged);
			},
			complete: function () {
				$('#directorist-dashboard-preloader').hide();
			}
		});
	}

	// Dashboard Listing Tabs
	$('.directorist-dashboard-listing-nav-js a').on('click', function(event) {
		var $item = $(this);

		if ($item.hasClass('directorist-tab__nav__active')) {
			return false;
		}

		directorist_dashboard_listing_ajax($item);
		$('#directorist-dashboard-listing-searchform input[name=searchtext').val('');
		$('#directorist-dashboard-mylistings-js').data('search','');

		return false;
	});

	// Dashboard Tasks eg. delete
	$('.directorist-dashboard-listings-tbody').on('click', '.directorist-dashboard-listing-actions-js a[data-task]', function(event) {
		var task       = $(this).data('task');
		var postid     = $(this).closest('tr').data('id');
		var $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
		var paged      = $('#directorist-dashboard-mylistings-js').data('paged');
		var search     = $('#directorist-dashboard-mylistings-js').data('search');

		if (task=='delete') {
			directoristModalAlert({
				title: atbdp_public_data.listing_remove_title,
				text: atbdp_public_data.listing_remove_text,
				type: 'warning',
				action: true,
				okButtonText: atbdp_public_data.review_delete_msg,
				okButtonUniqueId: 'directorist-remove-listing-ok'
			});

			$('#directorist-remove-listing-ok').on('click', function(){
				directorist_dashboard_listing_ajax($activeTab,paged,search,task,postid);

				// show success message
				directoristModalAlert({
					title: atbdp_public_data.listing_delete,
					type: 'success',
					timeout: 200,
					action: false
				});
			});
		}

		return false;
	});

	// Dashboard pagination
	$('.directorist-dashboard-pagination').on('click', 'a', function(event) {
		var $link = $(this);
		var paged = $link.attr('href');
		paged = paged.split('/page/')[1];
		paged = parseInt(paged);

		var search = $('#directorist-dashboard-mylistings-js').data('search');

		$activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
		directorist_dashboard_listing_ajax($activeTab,paged,search);

		return false;
	});

	// Dashboard Search
	$('#directorist-dashboard-listing-searchform input[name=searchtext').val(''); //onready
	
	$('#directorist-dashboard-listing-searchform').on('submit', function(event) {
		var $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
		var search = $(this).find('input[name=searchtext]').val();
		directorist_dashboard_listing_ajax($activeTab,1,search);
		$('#directorist-dashboard-mylistings-js').data('search',search);
		return false;
	});

})(jQuery);
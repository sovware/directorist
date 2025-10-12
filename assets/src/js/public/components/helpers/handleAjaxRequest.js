(function ($) {
	/*This function handles all ajax request*/
	function atbdp_do_ajax(
		ElementToShowLoadingIconAfter,
		ActionName,
		arg,
		CallBackHandler
	) {
		var data;
		if (ActionName) data = 'action=' + ActionName;
		if (arg) data = arg + '&action=' + ActionName;
		if (arg && !ActionName) data = arg;
		//data = data ;

		var n = data.search(directorist.nonceName);
		if (n < 0) {
			data = data + '&' + directorist.nonceName + '=' + directorist.nonce;
		}

		jQuery.ajax({
			type: 'post',
			url: directorist.ajaxurl,
			data: data,
			beforeSend: function () {
				jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(
					ElementToShowLoadingIconAfter
				);
			},
			success: function (data) {
				jQuery('.atbdp_ajax_loading').remove();
				CallBackHandler(data);
			},
		});
	}
	window.atbdp_do_ajax = atbdp_do_ajax;
})(jQuery);

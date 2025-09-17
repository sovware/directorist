(function ($) {
	$(document).ready(function () {
		$('#directorist-payment-receipt-retry-payment').on(
			'click',
			function () {
				var order_id = $(this).data('order-id');
				try {
					wp.apiFetch({
						path: '/directorist/checkout/retry-payment',
						method: 'POST',
						data: {
							order_id: order_id,
						},
					}).then(function (response) {
						window.location.href = response.redirect_url;
					});
				} catch (error) {
					console.log(error);
				}
			}
		);
	});
})(jQuery);

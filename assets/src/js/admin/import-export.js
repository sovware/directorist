jQuery(document).ready(function ($) {
	const query_string = (function (a) {
		if (a == '') return {};
		const b = {};
		for (let i = 0; i < a.length; ++i) {
			const p = a[i].split('=', 2);
			if (p.length == 1) b[p[0]] = '';
			else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
		}
		return b;
	})(window.location.search.substr(1).split('&'));

	$('body').on(
		'change',
		'.directorist_directory_type_in_import',
		function () {
			admin_listing_form($(this).val());
		}
	);

	function admin_listing_form(directory_type) {
		var file_id = query_string.file_id;
		var delimiter = query_string.delimiter;
		$.ajax({
			type: 'post',
			url: directorist_admin.ajaxurl,
			data: {
				action: 'directorist_update_csv_columns_to_listing_fields_table',
				directory_type: directory_type,
				delimiter: delimiter,
				directorist_nonce: directorist_admin.directorist_nonce,
				file_id: file_id,
			},
			beforeSend: function () {
				$('#directorist-type-preloader').show();
			},
			success(response) {
				if (response.error) {
					console.log({ response });
					return;
				}

				$('.atbdp-importer-mapping-table').remove();
				$('.directory_type_wrapper').after(response);
			},
			complete: function () {
				$('#directorist-type-preloader').hide();
			},
		});
	}

	$('#atbdp_csv_step_two').on('submit', function (e) {
		e.preventDefault();

		$('.atbdp-importer-mapping-table-wrapper').fadeOut(300);
		$('.directorist-importer__importing').fadeIn(300);
		$(this).parent('.csv-fields').fadeOut(300);
		$('.atbdp-mapping-step').removeClass('active').addClass('done');
		$('.atbdp-progress-step').addClass('active');

		$('.importer-details').html(`1/${$(this).data('total')}`);
		$('.directorist-importer-length').css('width', '10%');
		$('.directorist-importer-progress').val(10);

		const configFields = $('.directorist-listings-importer-config-field');

		const runImporter = function (position = 0, offset = 0) {
			const form_data = new FormData();

			form_data.set('action', 'directorist_import_listings');
			form_data.set('_position', position);
			form_data.set('_offset', offset);
			form_data.set(
				'directorist_nonce',
				directorist_admin.directorist_nonce
			);

			// Get Config Fields Value
			if (configFields.length) {
				configFields.each((index, item) => {
					const key = $(item).attr('name');
					const value = $(item).val();

					form_data.append(key, value);
				});
			}

			var map_elm = null;

			if ($('select.atbdp_map_to').length) {
				map_elm = $('select.atbdp_map_to');
			}

			if ($('input.atbdp_map_to').length) {
				map_elm = $('input.atbdp_map_to');
			}

			var directory_type = $('#directory_type').val();
			if (directory_type) {
				form_data.append('directory_type', directory_type);
			}

			if (map_elm) {
				var log = [];
				map_elm.each(function () {
					const name = $(this).attr('name');
					const value = $(this).val();

					const postFields = [
						'listing_status',
						'listing_title',
						'listing_content',
						'listing_img',
						'directory_type',
					];

					const taxonomyFields = ['category', 'location', 'tag'];

					if (postFields.includes(value)) {
						form_data.append(value, name);
						log.push({ [value]: name });
					} else if (taxonomyFields.includes(value)) {
						form_data.append(`tax_input[${value}]`, name);
						log.push({ [`tax_input[${value}]`]: name });
					} else if (value != '') {
						form_data.append(`meta[${value}]`, name);
						log.push({ [`meta[${value}]`]: name });
					}
				});
			}

			$.ajax({
				method: 'POST',
				processData: false,
				contentType: false,
				// async: false,
				url: directorist_admin.ajaxurl,
				data: form_data,
				success(response) {
					if (response.error) {
						console.log({ response });
						return;
					}

					const percentage =
						(response.position / response.total) * 100;

					$('.importer-details').html(
						`${Math.min(response.position, response.total)}/${response.total}`
					);
					$('.directorist-importer-length').css(
						'width',
						percentage + '%'
					);
					$('.directorist-importer-progress').val(percentage);

					console.log(response.logs.join('\n'));

					if (!response.done) {
						runImporter(response.position, response.offset);
					} else {
						window.location = response.redirect_url;
					}
				},
				error(response) {
					window.console.log(response);
				},
			});
		};

		runImporter();
	});

	/* csv upload */
	$('#upload').change(function (e) {
		const filename = e.target.files[0].name;
		$('.csv-upload .file-name').html(filename);
	});
});

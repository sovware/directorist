jQuery(document).ready(function($) {
	
	function SelectText(element) {
		var doc = document
			, text = doc.getElementById(element)
			, range, selection
		;    
		if (doc.body.createTextRange) {
			range = document.body.createTextRange();
			range.moveToElementText(text);
			range.select();
		} else if (window.getSelection) {
			selection = window.getSelection();        
			range = document.createRange();
			range.selectNodeContents(text);
			selection.removeAllRanges();
			selection.addRange(range);
		}
	}
	
	function directorist_exim_prepere() {
			const $form = $( "#directorist-export-import-form" ); //sets variable for the form
			
			$("#directorist-export-import-results").html('<strong>Loading...</strong>'); //sets text while loading in pre
			
			const author_ready = []; //prepares data for authors
			$.each($( 'input[name="author[]"]:checked' ), function() {
				author_ready.push($(this).val());
			});
			
			const ptype_ready = []; //prepares data for post type(ptype)
			$.each($( 'input[name="ptype[]"]:checked' ), function() {
				ptype_ready.push($(this).val());
			});
			
			const post_status_ready = []; //prepares data for post status
			$.each($( 'input[name="post_status[]"]:checked' ), function() {
				post_status_ready.push($(this).val());
			});		
			
			const taxonomies_ready = {}; //picks taxonomies and loops trough data
			$.each($( 'input[name*="taxonomy"]' ), function() {
				let taxonomy_name = $(this).attr("name").replace('][]','');
				 taxonomy_name = taxonomy_name.replace('taxonomy[','');
				
				if( taxonomy_name in taxonomies_ready === false ) {//if taxonomy not checked, data is picked
					taxonomies_ready[taxonomy_name] = {};
					let counter = 0;
					$.each($( 'input[name="taxonomy['+taxonomy_name+'][]"]:checked' ), function() {
						taxonomies_ready[taxonomy_name][counter] = $(this).val();
						counter = counter+1;
					});
					$.each($( 'input[name="taxonomy['+taxonomy_name+'][inex]"]:checked' ), function() {
						taxonomies_ready[taxonomy_name]['inex'] = $(this).val();
					});						
				}			
			});
			
			var data_filter_ready = []; //prepares data for pdata filter
			$.each($( 'input[name="data_filter[]"]:checked' ), function() {
				data_filter_ready.push($(this).val());
			});	
				
			return { //looks for and sets all variables used for export
				action: 'directorist_export_ajax',
				sdate: $form.find( 'select[name="sdate"]' ).val(),
				edate: $form.find( 'select[name="edate"]' ).val(),
				author_inex: $form.find( 'input[name="author_inex"]:checked' ).val(),
				author: author_ready,
				ptype: ptype_ready,
				taxonomy: taxonomies_ready,
				post_status: post_status_ready,
				data_filter: data_filter_ready,
				download: 0
			};
			

			
	}
		
	$('.checkbox_with_all').find('input').click(function(event) {
		if($(this).hasClass('e2t_all_input')) {
			if( $(this).is(":checked") ) {
				$(this).closest('.checkbox_with_all').find('input.e2t_input').attr('checked', false)			
			}
		}
		if($(this).hasClass('e2t_input')) {
			$(this).closest('.checkbox_with_all').find('input.e2t_all_input').attr('checked', false)
		}		
	});
	
	$("#directorist-export-import-results").click(function(event){
		SelectText('directorist-export-import-results-results');
	});
	
	$("a.button-secondary").click(function(event){ // function launched when submiting form
		
		event.preventDefault(); //disable default behavior
		
		$("#directorist-export-import-results-holder").show();
		
		const data = directorist_exim_prepere();
		
		$.post(ajaxurl, data, function(data){ //post data to specified action trough special WP ajax page
			console.log(data);
			$("#directorist-export-import-results").html(data); //displays data in pre
		});

	});
	
	$("#directorist-export-import-results-close").click(function(event){
		event.preventDefault(); //disable default behavior
		$("#directorist-export-import-results-holder").hide();
	});
	
	//enables sortable magic
	const $sortable = $( ".sortable" );
	$sortable.sortable();
	$sortable.disableSelection();
});
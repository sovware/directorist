<div class="wrap">
	<h2><?php echo $set->get_title(); ?></h2>
	<div id="vp-wrap" class="vp-wrap">
		<div id="vp-option-panel"class="vp-option-panel <?php echo ($set->get_layout() === 'fixed') ? 'fixed-layout' : 'fluid-layout' ; ?>">
			<div class="vp-left-panel">
				<div id="vp-logo" class="vp-logo">
					<img src="<?php echo VP_Util_Res::img($set->get_logo()); ?>" alt="<?php echo $set->get_title(); ?>" />
				</div>
				<div id="vp-menus" class="vp-menus">
					<ul class="vp-menu-level-1">
						<?php foreach ($set->get_menus() as $menu): ?>
						<?php $menus          = $set->get_menus(); ?>
						<?php $is_first_lvl_1 = $menu === reset($menus); ?>
						<?php if ($is_first_lvl_1): ?>
						<li class="vp-current">
						<?php else: ?>
						<li>
						<?php endif; ?>
							<?php if ($menu->get_menus()): ?>
							<a href="#<?php echo $menu->get_name(); ?>" class="vp-js-menu-dropdown vp-menu-dropdown">
							<?php else: ?>
							<a href="#<?php echo $menu->get_name(); ?>" class="vp-js-menu-goto vp-menu-goto">
							<?php endif; ?>
								<?php
								$icon = $menu->get_icon();
								$font_awesome = VP_Util_Res::is_font_awesome($icon);
								if ($font_awesome !== false):
									VP_Util_Text::print_if_exists($font_awesome, '<i class="fa %s"></i>');
								else:
									VP_Util_Text::print_if_exists(VP_Util_Res::img($icon), '<i class="custom-menu-icon" style="background-image: url(\'%s\');"></i>');
								endif;
								?>
								<span><?php echo $menu->get_title(); ?></span>
							</a>
							<?php if ($menu->get_menus()): ?>
							<ul class="vp-menu-level-2">
								<?php foreach ($menu->get_menus() as $submenu): ?>
								<?php $submenus = $menu->get_menus(); ?>
								<?php if ($is_first_lvl_1 and $submenu === reset($submenus)): ?>
								<li class="vp-current">
								<?php else: ?>
								<li>
								<?php endif; ?>
									<a href="#<?php echo $submenu->get_name(); ?>" class="vp-js-menu-goto vp-menu-goto">
										<?php
										$sub_icon = $submenu->get_icon();
										$font_awesome = VP_Util_Res::is_font_awesome($sub_icon);
										if ($font_awesome !== false):
											VP_Util_Text::print_if_exists($font_awesome, '<i class="fa %s"></i>');
										else:
											VP_Util_Text::print_if_exists(VP_Util_Res::img($sub_icon), '<i class="custom-menu-icon" style="background-image: url(\'%s\');"></i>');
										endif;
										?>
										<span><?php echo $submenu->get_title(); ?></span>
									</a>
								</li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="vp-right-panel">
                <div class="atbdp_searchable_settings">
                    <input class="vp-input input-large" id="atbdp_sSearch" autocomplete="off" placeholder="<?php _e('Search settings here...', ATBDP_TEXTDOMAIN); ?>" type="text">
                </div>

				<form id="vp-option-form" class="vp-option-form vp-js-option-form" method="POST">
					<div id="vp-submit-top" class="vp-submit top">
						<div class="inner search-wrapper">
                            <div class="atbdp_searchable">
                                <p class="vp-js-save-loader save-loader" style="display: none;"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" /><?php _e('Saving Now', ATBDP_TEXTDOMAIN); ?></p>
                                <p class="vp-js-save-status save-status" style="display: none;"></p>
                                <input class="vp-save vp-button button button-primary" type="submit" value="<?php _e('Save Changes', ATBDP_TEXTDOMAIN); ?>" />
                            </div>
						</div>
					</div>
					<?php foreach ($set->get_menus() as $menu): ?>
					<?php $menus = $set->get_menus(); ?>
					<?php if ($menu === reset($menus)): ?>
						<?php echo $menu->render(array('current' => 1)); ?>
					<?php else: ?>
						<?php echo $menu->render(array('current' => 0)); ?>
					<?php endif; ?>
					<?php endforeach; ?>
					<div id="vp-submit-bottom" class="vp-submit bottom">
						<div class="inner">
							<input class="vp-save vp-button button button-primary" type="submit" value="<?php _e('Save Changes', ATBDP_TEXTDOMAIN); ?>" />
							<p class="vp-js-save-loader save-loader" style="display: none;"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" /><?php _e('Saving Now', ATBDP_TEXTDOMAIN); ?></p>
							<p class="vp-js-save-status save-status" style="display: none;"></p>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>

<script>
	$(document).ready(() => {
		$('.atbdp_searchable_settings').append('<div class="search_detail"></div>');
		// data get use gloval variable
		const 	data_arr = [],
				data_arr_el = [],
				data = $('#vp-wrap').find('p, label, span'),
				data_split = data,
				s_index = [];

		$('#vp-wrap').find('h3, h1, h4, h5').map((index, el) => {
			data_arr.push(el.innerText.trim().toLowerCase());
			data_arr_el.push(el);
		})
		data_split.map((key, value) => {
			const text = value.innerText;
			if(text !== undefined ){
				data_arr.push(text.trim().toLowerCase());
				data_arr_el.push(value);
				s_index.push(key);
			}
		});

		// search section
		var search = document.querySelector('#atbdp_sSearch');
		var filter3 = null;
		$(search).on('keyup', (e) => {
			//filter for search
			var val = e.target.value.toLowerCase();
			var filter = data_arr.filter((el, index) => {
				return el.startsWith(val);
			});

			// data filter for add subtitle
			var search_store = [];
			var filter2 = data_arr_el.filter((el, index) => {
				return el.innerText.trim().toLowerCase().startsWith(val)
			})
			filter3 = filter2;
			if(val !== '') {
				filter2.map((key, value) => {
					if(key.closest('.vp-right-panel' && '.vp-panel')){
						var panel_id = key.closest('.vp-panel').getAttribute('id');
						search_store.push($(`a[href=#${panel_id}]`).text().trim());
					} else if(key.closest('.vp-left-panel' && '.vp-menu-level-1')){
						search_store.push($(key).closest('a').text().trim());
					}
				})
			}

			// filter data and insert data
			var filter_item = '<ul>';
			filter.map((el, index) =>{
				filter_item += `<li><a href="#" class="s_item" index=${index}>${el} <b>(${search_store[index]})</b></a></li>`;
			});
			filter_item += '</ul>';
			$('.search_detail').addClass('active');
			if(e.target.value) {
				$('.search_detail').html(filter_item);
			} else {
				$('.search_detail').html('');
			}
		});

		// click to tab	for data finding
		$('body').on('click', '.s_item', (e) => {
			var tg_content = '';
			var el_len = [];
			e.preventDefault();
			tg_content = e.target.text;
			tg_index 	= e.target.getAttribute('index');

			$(search).val(tg_content);

			filter3.map((el, index) => {
				el_len.push(el);
			})

			if(el_len[tg_index].closest('.vp-right-panel' && '.vp-panel')){
				var panel_id = el_len[tg_index].closest('.vp-panel').getAttribute('id');

				// click to tab
				$(`a[href=#${panel_id}]`).click();
				// animation add
				var body = $("html, body");
				if(el_len[tg_index].closest('.vp-section')){
					body.stop().animate({scrollTop: el_len[tg_index].closest('.vp-section').offsetTop}, 500, 'swing');				
				} else {
					body.stop().animate({scrollTop: el_len[tg_index].offsetTop}, 500, 'swing');				
				}
				
				if(el_len[tg_index].closest('.vp-field')) {
					el_len[tg_index].closest('.vp-field').classList.add('vp_select');
				} else if(el_len[tg_index].closest('.vp-section')) {
					el_len[tg_index].closest('.vp-section').classList.add('vp_select');
				}

			} else if(el_len[tg_index].closest('.vp-left-panel')){
				el_len[tg_index].closest('a').click();
			}
			$('.search_detail').removeClass('active');
		})

		// arrow key and enter key functional start
		var count = 0;
		$(search).on('keyup', (e) => { //key event
			if(e.target.value !== ''){
				$('.search_detail a').removeClass('vp_item_active');
				// key code condition for up and down arrow
				if(e.keyCode === 40){
					count ++;
					if(count > $('.search_detail a').length -1){
						count = 0;
					}
				} else if(e.keyCode === 38){
					count --;
					if(count < 0){
						count = $('.search_detail a').length -1;
					}
				}
				
				var elemaent = $('.search_detail a'); // search item list
				if(elemaent.length){
					elemaent[count].classList.add('vp_item_active'); // search item list add class for active
				}
				// press enter key functional
				if(e.keyCode === 13) {
					e.preventDefault();
					e.stopPropagation();
					$(search).val(filter3[count].innerText.trim());
					if(filter3[count].closest('.vp-right-panel' && '.vp-panel')){
						var id = filter3[count].closest('.vp-panel').getAttribute('id');
						// click tab use enter key						
						$(`a[href=#${id}]`).click();
						// animation scroll top for enter key
						var body = $("html, body");
						if(filter3[count].closest('.vp-section')){
							body.stop().animate({scrollTop: filter3[count].closest('.vp-section').offsetTop}, 500, 'swing');
						} else {
							body.stop().animate({scrollTop: filter3[count].offsetTop}, 500, 'swing');
						}
						// select class add use enter key
						if(filter3[count].closest('.vp-field')) {
							filter3[count].closest('.vp-field').classList.add('vp_select');
						} else if(filter3[count].closest('.vp-section')) {
							filter3[count].closest('.vp-section').classList.add('vp_select');
						}

					}
				}
			}							
		});
		// select class remove
		$('.vp-save').on('click', () => {
			$('*').removeClass('vp_select');
		});

		// popup remove
		$('body').on('click', (e) => {
			$('.search_detail').removeClass('active');
		});
		// write css
		$('.search_detail').css({
			width : search.offsetWidth,
			left : search.offsetLeft+'px',
			top : search.offsetTop+search.offsetHeight+'px'
		});

	})
</script>
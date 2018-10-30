<?php

add_action( 'admin_menu', 'directorist_export_page_cb' );

if (!function_exists('directorist_export_date_options')){
    /**
     * Create the date options fields for exporting a given post type.
     *
     * @global wpdb      $wpdb      WordPress database abstraction object.
     * @global WP_Locale $wp_locale Date and Time Locale object.
     *
     * @since 3.1.0
     *
     * @param string $post_type The post type. Default 'post'.
     *
     * @return string it return the markup of option tags of date
     */
    function directorist_export_date_options( $post_type = 'post' ) {
        global $wpdb, $wp_locale;

        $months = $wpdb->get_results( $wpdb->prepare( "
		SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
		FROM $wpdb->posts
		WHERE post_type = %s AND post_status != 'auto-draft'
		ORDER BY post_date DESC
	", $post_type ) );

        $month_count = count( $months );
        if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
            return null;
        $output = '';
        foreach ( $months as $date ) {
            if ( 0 == $date->year )
                continue;

            $month = zeroise( $date->month, 2 );
            $output .= '<option value="' . $date->year . '-' . $month . '">' . $wp_locale->get_month( $month ) . ' ' . $date->year . '</option>';
        }
        return $output;
    }
}
/**
 *
 */
function directorist_export_page_cb() { // adds menu to "Tools" section linked to directorist_export_page function
	//$plugin_page = add_management_page('Export to Text', 'Export to Text', 'export', basename(__FILE__), 'directorist_export_page'); //Sets and saves plugin page in WP admin
    $plugin_page = add_submenu_page('edit.php?post_type=at_biz_dir', __('Directorist Export', ATBDP_TEXTDOMAIN), __('Directorist Export', ATBDP_TEXTDOMAIN), 'export', 'directorist-export', 'directorist_export_page');
	add_action( 'load-'.$plugin_page, 'directorist_exim_add_js' ); //triggers directorist_exim_add_js function just on plugin page
}

/**
 *
 */
function directorist_exim_add_js() { //properly adds JS file to page
	wp_enqueue_style( 'export_to_text_css', plugins_url( 'directorist-export-import.css' , __FILE__ ) );
	
	wp_enqueue_script( 'export_to_text_js', plugins_url( 'directorist-export-import.js' , __FILE__ ) );
	
	$params = array(
		'ajaxurl' => admin_url( 'admin-ajax.php')
	);
	wp_localize_script( 'export_to_text_js', 'export_to_text_js', $params );
	
	wp_enqueue_script( 'jquery-ui-sortable' );
}

require_once dirname( __FILE__ ) . '/directorist-export-import_helpers.php'; //loads file with functions for help
require_once dirname( __FILE__ ) . '/directorist-export-xml-generator.php'; //loads file with functions for help
require_once dirname( __FILE__ ) . '/directorist-importer.php'; //loads the importer

if(!empty($_POST['download'])) {
	
	add_action('wp_loaded', 'directorist_export_to_download', 1);

    /**
     *
     */
    function directorist_export_to_download() {

		/*$sitename = sanitize_key( get_bloginfo( 'name' ) );
		if ( ! empty($sitename) ) $sitename .= '.';
		$filename = $sitename . 'wordpress.' . date( 'Y-m-d' ) . '.txt';
	
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ), true );*/

        directorist_exp_to_xml();
		die();
	}
	
	//directorist_export_to_download();
}

/**
 * It outputs the settings page markup for the export to text plugin
 */
function directorist_export_page() { // Sre2t_manage function used to display Export To Text page
		if ( !current_user_can('export') )
			wp_die(__('You do not have sufficient permissions to export the content of this site.', ATBDP_TEXTDOMAIN));
	
		global $wpdb;
		
		// Displays Export To Text Menu
		?>
		<div class="directorist-export-import">
        <div class="wrap">
            <h2><?php esc_html_e('Directorist Exporter', ATBDP_TEXTDOMAIN); ?></h2>
            <h3><?php esc_html_e('Welcome to Directorist Exporter', ATBDP_TEXTDOMAIN); ?></h3>

            <div id="main" style="background: #ffffff;padding: 20px;line-height: 24px; width: 51%">
                
	            <p><?php esc_html_e('A simple plugin to export WordPress post data into a tab-separated text file format (TSV). When you click the button below Export to Text will render a text box from which you can copy and paste your data into a text editor or Excel. If you need to re-import posts from a text file, please consider using CSV Importer plugin.', ATBDP_TEXTDOMAIN); ?></p>
	            
	            <form id="directorist-export-import-form" action="" method="post"><!--Form posts to "directorist-export-import_dl_txt.php" responsible for file download-->
	                <h3><?php esc_html_e('Filters', ATBDP_TEXTDOMAIN); ?></h3>
	                <div id="options_holder">
	                	<div class="option_box option_box_short">
	                		<label id="sdate" class="short_label" for="sdate"><?php esc_html_e('Start Date', ATBDP_TEXTDOMAIN); ?></label>
	                        <select name="sdate" id="sdate">
	                        	<option value="all"><?php esc_html_e('All Dates', ATBDP_TEXTDOMAIN); ?></option>
	                        	<?php
								$dateoptions = directorist_export_date_options(ATBDP_POST_TYPE);
                                echo $dateoptions;

	                        	?>
	                        </select>                 		
	                	</div>
	                	<div class="option_box option_box_short">
	                		<label for="edate" class="short_label"><?php esc_html_e('End Date', ATBDP_TEXTDOMAIN); ?></label>
	                        <select name="edate" id="edate">
	                             <option value="all"><?php esc_html_e('All Dates', ATBDP_TEXTDOMAIN); ?></option>
								 <?php
	                             echo $dateoptions;
	                             ?>
	                        </select>
	                	</div>
	                	<div class="option_box option_box_submit submit">
			                <div style="display:flex;">
                                <input type="hidden" name="download" value="<?php echo get_home_path(); ?>" />

                                <a href="#" class="button-secondary" style="margin-right: 40px;"><?php esc_html_e('Generate Preview (10 max)', ATBDP_TEXTDOMAIN); ?></a> <!--link connected to js responsible for AJAX call-->
                                <input class="button button-primary" type="submit" value="Download as XML file" name="submit"> <!--Posts data to "directorist-export-import_dl_txt.php" file-->
                            </div>
	                	</div>               	
	                </div>
	                	
	            </form>
	            <div class="clearboth"></div>
	            
	            <div id="directorist-export-import-results-holder">
					<div id="directorist-export-import-results-close-holder"><a href="#" id="directorist-export-import-results-close">Close</a></div>
	            	<div id="directorist-export-import-results" >
                        <strong>
                            <?php esc_html_e('Just click on "Generate for quick copying" and then click on this box to select and copy the text. Then paste it (Paste Special works best) into a new Excel document.', ATBDP_TEXTDOMAIN); ?>
                        </strong>
                    </div>
	            </div>
                
                <div class="clearboth"></div>
                            
            </div>
        </div>
        </div>
<?php	
}

//add_action( 'wp_ajax_directorist_exim_ajax', 'directorist_export_ajax' ); //adds function to WP ajax

add_action( 'wp_ajax_directorist_export_ajax', 'directorist_exp_to_xml' ); //adds function to WP ajax
/**
 *
 */
function directorist_exp_to_xml(){
    // save few chars
    $r= $_REQUEST;
    $args = array(
        'content' => 'at_biz_dir',
        'author' => !empty($r['author']) ? sanitize_text_field($r['author']) : false,
        'category' => false,
        'start_date' => !empty($r['sdate']) ? sanitize_text_field($r['sdate']) : false,
        'end_date' => !empty($r['edate']) ? sanitize_text_field($r['edate']) : false,
        'status' => false,
    );
    export_directorist($args);
    die();
}
function directorist_export_ajax() { //Function used for generating results for display in PRE tag and saving as TXT
	// sets correct values for start and end date + adds WP "post_where" filter
    // @improve date filter, see includes/export.php file for inspiration
	if ( ($_POST['sdate'] != 'all' || $_POST['edate'] != 'all') && !empty($_POST['sdate']) ) {
		add_filter('posts_where', 'filter_where');
		function filter_where($where = '') {
			global $wpdb;
            // limit post by start date
            if ( $_POST['sdate'] ){
                $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_date >= %s", date( 'Y-m-d', strtotime($_POST['sdate']) ) );
            }
            // limit post by end date
            if ( $_POST['edate'] ){
                $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_date < %s", date( 'Y-m-d', strtotime('+1 month', strtotime($_POST['edate'])) ) );
            }

			return $where;
		}
	}
	
	if( $_POST['download'] == '0' )
		$posts_per_page = 10;
	else
		$posts_per_page = -1;
	
	$args = array( // arguments used for WP_Query
		'posts_per_page' => $posts_per_page,
		'post_type' => ATBDP_POST_TYPE,
		'post_status' => sanitize_text_field($_POST['post_status']),
		'order' => ASC,
	);
	//creates arrays for taxonomies
	$is_tax_query = 0;
	foreach ($_POST['taxonomy'] as $key => $value) {
		if( !in_array('e2t_all', $value) ) {
			if($is_tax_query == 0) {
				$args['tax_query'] = array('relation' => 'OR');
				$is_tax_query = 1;
			}
			$operator = $value['inex'];
			unset($value['inex']);
			$temp = array( 'taxonomy' => $key, 'field' => 'id', 'terms' => $value, 'operator' => $operator );
			array_push($args['tax_query'], $temp);
		}
	}
	//adds argument for authors
	if( !in_array('e2t_all', $_POST['author']) ) {
		$args['author'] = $_POST['author_inex'].implode(','.$_POST['author_inex'], $_POST['author']);
	}
	$export_to_text = new WP_Query( $args ); // new custom loop to get desired results
	
	//prepare all the post data...if there are any posts!
	if ( $export_to_text->have_posts() ) {

        $ett_posts = array();
        $count = 0;

        if (!is_array($_POST['data_filter'])) {
            $_POST['data_filter'] = array();
        }


        while ($export_to_text->have_posts()) {
            $export_to_text->the_post();

            if (in_array('ID', $_POST['data_filter'])) $ett_posts[$count]['ID'] = get_the_ID();
            if (in_array('Title', $_POST['data_filter'])) $ett_posts[$count]['Title'] = get_the_title();
            if (in_array('Date', $_POST['data_filter'])) $ett_posts[$count]['Date'] = get_the_date();
            if (in_array('Post Type', $_POST['data_filter'])) $ett_posts[$count]['Post Type'] = get_post_type();

            if (in_array('Categories', $_POST['data_filter'])) {
                $categories_names = array();
                foreach (get_the_category() as $category) {
                    $categories_names[] = $category->cat_name;
                }
                $ett_posts[$count]['Categories'] = implode(', ', $categories_names);
            }

            if (in_array('Tags', $_POST['data_filter'])) {
                if (has_tag()) {
                    $tags_names = array();
                    foreach (get_the_tags() as $tag) {
                        $tags_names[] = $tag->name;
                    }
                    $ett_posts[$count]['Tags'] = implode(', ', $tags_names);
                } else {
                    $ett_posts[$count]['Tags'] = "";
                }
            }

            if (in_array('Custom Taxonomies', $_POST['data_filter'])) $ett_posts[$count]['Custom Taxonomies'] = directorist_exim_custom_taxonomies_terms_links();

            if (in_array('Permlink', $_POST['data_filter'])) $ett_posts[$count]['Permlink'] = get_post_permalink(get_the_ID());

            if (in_array('Content', $_POST['data_filter'])) {
                global $more;
                $more = 1;
                $thepostcontent = apply_filters('the_content', get_the_content());
                //$thepostcontent = get_the_content();
                $thepostcontent = htmlentities($thepostcontent, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                $thepostcontent = preg_replace('/[\t\r\n]*/', '', $thepostcontent);
                $ett_posts[$count]['Content'] = $thepostcontent;
            }

            if (in_array('Excerpt', $_POST['data_filter'])) {
                $thepostexcerpt = htmlentities(get_the_excerpt(), ENT_QUOTES | ENT_IGNORE, "UTF-8");
                $thepostexcerpt = preg_replace('/[\t\r\n]*/', '', $thepostexcerpt);
                $ett_posts[$count]['Excerpt'] = $thepostexcerpt;
            }

            if (in_array('Author', $_POST['data_filter'])) $ett_posts[$count]['Author'] = get_the_author();
            if (in_array('Author Email', $_POST['data_filter'])) $ett_posts[$count]['Author Email'] = get_the_author_email();

            if (in_array('Custom Fields', $_POST['data_filter'])) {
                $custom_field_keys = get_post_custom_keys();
                if (!empty($custom_field_keys)) {
                    sort($custom_field_keys);
                    $custom_field_keys_ready = array();

                    foreach ($custom_field_keys as $key => $value) {
                        $valuet = trim($value);
                        if ('_' != $valuet{0}) {
                            $mykey_values = get_post_custom_values($value);
                            sort($mykey_values);
                            foreach ($mykey_values as $key2 => $value2) {
                                $custom_field_keys_ready[] = htmlentities(preg_replace('/[\t\r\n]*/', '', "$value => $value2"), ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            }
                        }
                    }

                    $ett_posts[$count]['Custom Fields'] = implode('. ', $custom_field_keys_ready);
                } else {
                    $ett_posts[$count]['Custom Fields'] = "";
                }
            }

            if (in_array('Comments', $_POST['data_filter'])) {
                $args = array(
                    'status' => 'approve',
                    'post_id' => get_the_ID()
                );
                $comments = get_comments($args);

                if (!empty($comments)) {
                    $comments_ready = array();

                    foreach ($comments as $comment) {
                        $comment_content = htmlentities($comment->comment_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                        $comment_content = preg_replace('/[\t\r\n]*/', '', $comment_content);

                        $comments_ready[] = $comment->comment_author . ' => ' . $comment_content;
                    }
                    $ett_posts[$count]['Comments'] = implode('. ', $comments_ready);
                } else {
                    $ett_posts[$count]['Comments'] = "";
                }
            }

            $count++;

        };
    }else {
        $ett_posts = 0;
    }
	
	//after data is ready - now its time for show!
	if(count($ett_posts) > 0) {
		
		if( $_POST['download'] == '0' ) {
			$begin = '<table class="wp-list-table widefat fixed"><thead><tr>'.directorist_exim_implode_wrapped('<th>', '</th>', $_POST['data_filter']).'</tr></thead><tbody>';
			$begin_row = '<tr>';
			$end_row = '</tr>';
			$end = '</tbody></table>';
		}
		else {
			$begin = implode("\t", $_POST['data_filter'])."\r\n";
			$begin_row = '';
			$end_row = "\r\n";
			$end = '';
		}
		
		echo $begin;
			
		foreach($ett_posts as $ett_post) {
			echo $begin_row;
					
			foreach($_POST['data_filter'] as $data) {
				if( $_POST['download'] == '0' )
					echo '<td>'.$ett_post[$data].'</td>';
				else {
					$data = html_entity_decode($ett_post[$data]);
					if(strlen($data) > 32752)
						$data = substr($data, 0, 32752).'[limit]'."\t";
					echo $data."\t";
				}
			}
			
			echo $end_row;
		}
		
		echo $end;
	}
					
	if ( ($_POST['sdate'] != 'all' || $_POST['edate'] != 'all') && !empty($_POST['sdate']) ) { remove_filter('posts_where', 'filter_where'); }
	
	die(); //Functions echoing for AJAX must die
}


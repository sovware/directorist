<?php
$categories                  = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
$locations                   = get_terms(ATBDP_LOCATION, array('hide_empty' => 0));
// get search page title and sub title from the plugin settings page
$search_title                = get_directorist_option('search_title', '');
$search_subtitle             = get_directorist_option('search_subtitle', '');
$search_placeholder          = get_directorist_option('search_placeholder', __('What are you looking for?', ATBDP_TEXTDOMAIN));

$show_popular_category       = get_directorist_option('show_popular_category', 1);
$show_connector              = get_directorist_option('show_connector', 1);
$search_border               = get_directorist_option('search_border', 1);

$connectors_title            = get_directorist_option('connectors_title', __('Or', ATBDP_TEXTDOMAIN));
$popular_cat_title           = get_directorist_option('popular_cat_title', __('Browse by popular categories', ATBDP_TEXTDOMAIN));
$popular_cat_num             = get_directorist_option('popular_cat_num', 10);
$display_category_field      = get_directorist_option('display_category_field', 1);
$display_location_field      = get_directorist_option('display_location_field', 1);
$display_text_field          = get_directorist_option('display_text_field', 1);

$default                     = get_template_directory_uri().'/images/home_page_bg.jpg';
$theme_home_bg_image         = get_theme_mod('directoria_home_bg');
$search_home_bg              = get_directorist_option('search_home_bg');
$front_bg_image              = (!empty($theme_home_bg_image)) ? $theme_home_bg_image : $search_home_bg;
?>
<!-- start search section -->
<div id="directorist" class="directorist atbd_wrapper directory_search_area single_area"
     style="background-image: url('<?php echo (!empty($front_bg_image)) ? esc_url($front_bg_image) : esc_url($default); ?>')">
    <!-- start search area container -->
    <div class="<?php echo is_directoria_active() ? 'container' : 'container-fluid'; ?>">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($search_title) || !empty($search_subtitle)) { ?>
                    <div class="atbd_search_title_area">
                        <?php echo !empty($search_title) ? '<h2 class="title">' . esc_html($search_title) . '</h2>' : ''; ?>
                        <?php echo !empty($search_subtitle) ? '<p class="sub_title">' . esc_html($search_subtitle) . '</p>' : ''; ?>
                    </div><!--- end title area -->
                <?php } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- start search area -->
                <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
                    <!-- @todo; if the input fields break in different themes, use bootstrap form inputs then -->
                    <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border)?'style="border: none;"':'';?>>
                        <div class="row atbdp-search-form">
                            <?php if(!empty($display_text_field)) {?>
                            <div class="col-md-6 col-sm-12 col-lg-4">
                                <div class="single_search_field search_query">
                                    <input class="form-control search_fields" type="text" name="q"
                                           placeholder="<?php echo esc_html($search_placeholder); ?>">
                                </div>
                            </div>
                            <?php } ?>
                            <?php if(!empty($display_category_field)) {?>
                            <div class="col-md-6 col-sm-12 col-lg-4">
                                <div class="single_search_field search_category">
                                    <?php
                                    $args = array(
                                        'show_option_none' =>  __('Select a category', ATBDP_TEXTDOMAIN),
                                        'taxonomy' => ATBDP_CATEGORY,
                                        'id' => 'cat-type',
                                        'option_none_value'  => '',
                                        'class' => 'form-control directory_field',
                                        'name' => 'in_cat',
                                        'orderby' => 'name',
                                        'selected' => '',
                                        'hierarchical' => true,
                                        'value_field'  => 'slug',
                                        'depth' => 10,
                                        'show_count' => false,
                                        'hide_empty' => false,
                                    );

                                    wp_dropdown_categories($args);
                                    ?>
                                </div>
                            </div>
                            <?php }
                            if(!empty($display_location_field)) { ?>
                            <div class="col-md-12 col-sm-12 col-lg-4">
                                <div class="single_search_field search_location">
                                    <?php
                                    $args = array(
                                        'show_option_none' =>  __('Select a location', ATBDP_TEXTDOMAIN),
                                        'taxonomy' => ATBDP_LOCATION,
                                        'id' => 'cat-type',
                                        'option_none_value'  => '',
                                        'class' => 'form-control directory_field',
                                        'name' => 'in_loc',
                                        'orderby' => 'name',
                                        'selected' => '',
                                        'hierarchical' => true,
                                        'value_field'  => 'slug',
                                        'depth' => 10,
                                        'show_count' => false,
                                        'hide_empty' => false,
                                    );

                                    wp_dropdown_categories($args);
                                    ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="atbd_submit_btn">
                        <button type="submit" class="btn btn-primary btn-lg btn_search">
                            <span class="fa fa-search"></span> <?php _e('Search Listing', ATBDP_TEXTDOMAIN)?>
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <?php if (1 == $show_popular_category) {
                    /*@todo; let user decide what the popular category should be counted based on, and how to sort them*/
                    $args = array(
                        'type' => ATBDP_POST_TYPE,
                        'parent' => 0,          // Gets only top level categories
                        'orderby' => 'count',   // Orders the list by post count
                        'order' => 'desc',
                        'hide_empty' => 1,      // Hides categories with no posts
                        'number' => (int)$popular_cat_num,         // No of categories to return
                        'taxonomy' => ATBDP_CATEGORY,
                        'no_found_rows' => true, // Skip SQL_CALC_FOUND_ROWS for performance (no pagination).

                    );
                    $top_categories = get_categories($args); // do not show any markup if we do not have any category at all.
                    if (!empty($top_categories)) {
                        ?>
                        <div class="directory_home_category_area">
                            <?php
                            if ($show_connector == '1') {
                                ?>
                                <span><?php echo $connectors_title; ?></span>
                                <?php
                            }
                            ?>
                            <p><?php echo esc_html($popular_cat_title); ?></p>

                            <ul class="categories">
                                <?php
                                foreach ($top_categories as $cat) { ?>
                                    <li>
                                        <a href="<?= ATBDP_Permalink::atbdp_get_category_page($cat); ?>">
                                            <span class="fa <?= get_cat_icon($cat->term_id); ?>"
                                                  aria-hidden="true"></span>
                                            <p><?= $cat->name; ?></p>
                                        </a>
                                    </li>

                                <?php }
                                ?>
                            </ul>
                        </div><!-- End category area -->
                    <?php }
                } ?>
            </div>
        </div>
    </div><!-- end directory_main_area -->
</div><!-- end search area container -->
<!-- end search section -->



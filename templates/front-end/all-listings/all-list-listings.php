<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$pagenation = get_directorist_option('paginate_all_listings', 1);
$display_sortby_dropdown = get_directorist_option('display_sort_by', 1);
$display_viewas_dropdown = get_directorist_option('display_view_as', 1);

?>
    <div id="directorist" class="atbd_wrapper">
        <div class="<?php echo is_directoria_active() ? 'container' : 'container-fluid'; ?>">
            <div class="row" data-uk-grid>
                <?php if( $display_header == 'yes'  ) { ?>
                    <div class="col-md-12">
                        <div class="atbd_generic_header">
                            <div class="atbd_generic_header_title">
                                <?php if (!empty($header_title)) {?>
                                <h3>
                                    <?php echo esc_html($header_title); ?>
                                </h3>
                                <?php } ?>
                                <?php
                                echo esc_html($header_sub_title)  . ' ';
                                if ($paginate) {
                                    echo $all_listings->found_posts;
                                } else {
                                    echo count($all_listings->posts);
                                }
                                ?>
                            </div>
                            <?php if ($display_viewas_dropdown || $display_sortby_dropdown) { ?>
                                <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                    <?php if ($display_viewas_dropdown) { ?>
                                        <div class="dropdown">
                                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                               id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <?php _e("View as", ATBDP_TEXTDOMAIN); ?> <span class="caret"></span>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <?php
                                                $views = atbdp_get_listings_view_options();

                                                foreach ($views as $value => $label) {
                                                    $active_class = ($view == $value) ? ' active' : '';
                                                    printf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('view', $value), $label);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <!-- Orderby dropdown -->
                                    <?php if ($display_sortby_dropdown) { ?>
                                        <div class="dropdown">
                                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                               id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <?php _e("Sort by", ATBDP_TEXTDOMAIN); ?> <span class="caret"></span>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                <?php
                                                $options = atbdp_get_listings_orderby_options();

                                                foreach ($options as $value => $label) {
                                                    $active_class = ($value == $current_order) ? ' active' : '';
                                                    printf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('sort', $value), $label);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($all_listings->have_posts()) { ?>
                    <?php listing_view_by_list($all_listings);?>
                <?php } else { ?>
                    <p><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
                <?php } ?>

            </div> <!--ends .row -->

            <?php
            if (1 == $pagenation) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $paged = !empty($paged)?$paged:'';
                        echo atbdp_pagination($all_listings, $paged);
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

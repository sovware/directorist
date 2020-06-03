<div class="atbd_single_listing atbd_listing_list">
   <article class="atbd_single_listing_wrapper <?php echo ($listings->loop['featured']) ? 'directorist-featured-listings' : ''; ?>">
        <figure class="atbd_listing_thumbnail_area" style="<?php echo (!$listings->display_preview_image) ? 'display:none' : '' ?>">
            <?php if (!$listings->disable_single_listing) { ?>
                <a href="<?php echo esc_url($listings->loop['permalink']); ?>" <?php echo $listings->loop_thumbnail_link_attr(); ?>>
                <?php
            }

            atbdp_thumbnail_card();

            if (!$listings->disable_single_listing) {
                echo '</a>';
            } ?>

            <!-- Start lower badge -->
            <span class="atbd_lower_badge">
                <?php
                /**
                 * @since 5.0
                 *
                 * @hooked Directorist_Template_Hooks::featured_badge_list_view - 10
                 * @hooked Directorist_Template_Hooks::populer_badge_list_view - 15
                 * @hooked Directorist_Template_Hooks::new_badge_list_view - 20
                 */
                echo apply_filters( 'atbdp_list_lower_badges', '' );
                ?>
            </span>
        </figure>

        <div class="atbd_listing_info">
            <div class="atbd_content_upper">
                <?php do_action( 'atbdp_list_view_before_title' );?>
                <?php if ($listings->display_title) { ?>
                    <h4 class="atbd_listing_title">
                        <?php
                        if (!$listings->disable_single_listing) {
                          echo '<a href="' . esc_url(get_post_permalink(get_the_ID())) . '"' . $listings->loop_title_link_attr() . '>' . esc_html(stripslashes(get_the_title())) . '</a>';
                        } else {
                          echo esc_html(stripslashes(get_the_title()));
                        } ?>
                    </h4>
                <?php }

                /**
                 * @since 6.2.3
                 */
                do_action( 'atbdp_list_view_after_title' );
                if (!empty($listings->loop['tagline']) && $listings->enable_tagline && $listings->display_tagline_field) { ?>
                    <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($listings->loop['tagline'])); ?></p>
                    <?php
                }

                /**
                 * Fires after the title and sub title of the listing is rendered
                 *
                 *
                 * @since 1.0.0
                 */
                do_action( 'atbdp_after_listing_tagline' );

                if ($listings->display_review || ($listings->display_price && (!empty($listings->loop['price']) || !empty($listings->loop['price_range'])))) { ?>
                    <div class="atbd_listing_meta">
                        <?php
                        /**
                         * @hooked Directorist_Template_Hooks::review_in_list_review_price - 10
                         * @hooked Directorist_Template_Hooks::price_in_list_review_price - 15
                         * @hooked Directorist_Template_Hooks::business_hour_in_list_review_price - 20
                         */
                        echo apply_filters( 'atbdp_listings_list_review_price', '' ); ?>
                    </div>
                <?php }


                if ($listings->display_contact_info || $listings->display_publish_date || $listings->display_email || $listings->display_web_link) {
                    $listings->loop_data_list_html();
                }
                
                // show category and location info
                if (!empty($listings->loop['excerpt']) && $listings->enable_excerpt && $listings->display_excerpt_field) {
                    ?>
                    <p class="atbd_excerpt_content">
                        <?php echo esc_html(stripslashes(wp_trim_words($listings->loop['excerpt'], $listings->excerpt_limit)));
                    
                        /**
                         * @since 5.0.9
                         */
                        do_action( 'atbdp_listings_after_exerpt' );
                        if ($listings->display_readmore) { ?>
                            <a href="<?php the_permalink();?>"><?php printf( __( ' %s', 'directorist' ), $listings->readmore_text );?></a>
                        <?php } ?>
                    </p>
                <?php } 

                if ($listings->display_mark_as_fav) {
                    echo apply_filters( 'atbdp_mark_as_fav_for_list_view', atbdp_listings_mark_as_favourite( get_the_ID() ) );
                }
                ?>
            </div><!-- end ./atbd_content_upper -->

            <?php ob_start();
            if ( $listings->display_category || $listings->display_view_count || $listings->display_author_image ) { ?>
                <div class="atbd_listing_bottom_content">
                <?php if ( $listings->display_category ) { if ( ! empty( $listings->loop['cats'] ) ) {$totalTerm = count( $listings->loop['cats'] ); ?>
                    <div class="atbd_content_left">
                        <div class="atbd_listing_category">
                            <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page( $listings->loop['cats'][0] ); ?>">
                                <span class="<?php echo atbdp_icon_type(); ?>-tags"></span>
                                <?php echo $listings->loop['cats'][0]->name; ?>
                            </a>
                            <?php if ( $totalTerm > 1 ) {$totalTerm = $totalTerm - 1;?>
                            <div class="atbd_cat_popup">
                                <span><?php echo $totalTerm; ?></span>
                                <div class="atbd_cat_popup_wrapper">
                                    <span>
                                        <?php foreach ( array_slice( $listings->loop['cats'], 1 ) as $cat ) {
                                        $link  = ATBDP_Permalink::atbdp_get_category_page( $cat );
                                        $space = str_repeat( ' ', 1 );
                                        echo $space;?>
                                        <span>
                                            <a href='<?php echo $link; ?>'><?php echo $cat->name; ?><span>,</span></a>
                                        </span>
                                    <?php }?>
                                    </span>
                                </div>
                            </div>
                        <?php }?>
                        </div>
                    </div>
                    <?php } else { ?>

                    <div class="atbd_content_left">
                        <div class="atbd_listing_category">
                            <a href="./">
                                <span class="<?php atbdp_icon_type()?>-tags"></span>
                                <?php _e( 'Uncategorized', 'directorist' );?>
                            </a>
                        </div>
                    </div>
                <?php }}

                if ( $listings->display_view_count || $listings->display_author_image ) {?>
                    <ul class="atbd_content_right">
                        <?php if ( $listings->display_view_count ) { ?>
                        <li class="atbd_count">
                            <span class="<?php atbdp_icon_type();?>-eye"></span>
                            <?php echo ! empty( $listings->loop['post_view'] ) ? $listings->loop['post_view'] : 0; ?>
                        </li>
                        <?php }
                        if ( $listings->display_author_image ) {
                        ?>
                        <li class="atbd_author">
                            <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link( $listings->loop['author_id'] ); ?>"
                                class="<?php echo $listings->loop['author_link_class']; ?>"
                                aria-label="<?php echo $listings->loop['author_full_name']; ?>">
                                <?php if ( empty( $listings->loop['u_pro_pic'] ) ) {echo $listings->loop['avatar_img'];}
                                if ( ! empty( $listings->loop['u_pro_pic'] ) ) { ?>
                                <img src="<?php echo esc_url( $listings->loop['u_pro_pic'][0] ); ?>" alt="Author Image">
                                <?php } ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
                </div>
            <?php }
            echo apply_filters( 'atbdp_listings_list_cat_view_count_author', ob_get_clean() );?>
        </div>
    </article>
</div>
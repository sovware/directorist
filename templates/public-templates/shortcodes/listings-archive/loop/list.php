<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

$featured_class = $listings->loop['featured'] ? 'directorist-featured-listings' : '';
?>
<div class="atbd_single_listing atbd_listing_list">
   <article class="atbd_single_listing_wrapper <?php echo esc_attr( $featured_class ); ?>">

        <?php if ( $listings->display_preview_image ): ?>

            <figure class="atbd_listing_thumbnail_area">
                <?php
                $listings->loop_thumb_card_template();

                /**
                 * @since 5.0
                 *
                 * @hooked Directorist_Template_Hooks::featured_badge_list_view - 10
                 * @hooked Directorist_Template_Hooks::populer_badge_list_view - 15
                 * @hooked Directorist_Template_Hooks::new_badge_list_view - 20
                 */
                ?>
                <span class="atbd_lower_badge"><?php echo apply_filters('atbdp_list_lower_badges', '');?></span>
            </figure>

        <?php endif; ?>

        <div class="atbd_listing_info">

            <?php 
            $listings->loop_top_content_template();

            ob_start();
            if ( $listings->display_category || $listings->display_view_count || $listings->display_author_image ) { ?>
                <div class="atbd_listing_bottom_content">
                <?php if ( $listings->display_category ) { if ( ! empty( $listings->loop['cats'] ) ) {$totalTerm = count( $listings->loop['cats'] ); ?>
                    <div class="atbd_content_left">
                        <div class="atbd_listing_category">
                            <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page( $listings->loop['cats'][0] ); ?>">
                                <span class="<?php echo atbdp_icon_type(true); ?>-tags"></span>
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
                                <span class="<?php atbdp_icon_type(true)?>-tags"></span>
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
<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.4.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$query->have_posts() ) {
    return;
}
$default_icon = 'las la-tags';
?>

<div class="atbd_categorized_listings">
    <ul class="listings">
        <?php while ( $query->have_posts() ): ?>

            <?php
            $query->the_post();
            $id = get_the_ID();
            $disable_single_listing = get_directorist_option('disable_single_listing');
            $top_category = ATBDP()->taxonomy->get_one_high_level_term($id, ATBDP_CATEGORY);
            $listing_img = get_post_meta($id, '_listing_img', true);
            $listing_prv_img = get_post_meta($id, '_listing_prv_img', true);
            $cats = get_the_terms($id, ATBDP_CATEGORY);
            $post_link = get_the_permalink( $id );

            ?>

            <li>

                <div class="atbd_left_img">
                    <?php if ( empty( $disable_single_listing) ) { ?>
                        <a href="<?php the_permalink(); ?>">
                        <?php
                    }
                    $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
                    if (!empty($listing_prv_img)) {
                        echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="' . esc_html(get_the_title()) . '">';
                    } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                        echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="' . esc_html(get_the_title()) . '">';
                    } else {
                        echo '<img src="' . esc_url( $default_image ) . '" alt="' . esc_html(get_the_title()) . '">';
                    }
                    if (empty($disable_single_listing)) {
                        echo '</a>';
                    }
                    ?>
                </div>

                <div class="atbd_right_content">

                    <div class="cate_title">
                        <h4>
                            <?php
                            if (empty($disable_single_listing)) {
                                ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <?php
                            } else {
                                the_title();
                            } ?>
                        </h4>
                    </div>

                        <div class="directorist-listing-category">
                        <?php if ( ! empty( $cats ) ) {
                            $term_icon  = get_term_meta( $cats[0]->term_id, 'category_icon', true );
                            $term_icon  = $term_icon ? $term_icon : $default_icon;
                            $term_link  = esc_url( get_term_link( $cats[0]->term_id, ATBDP_CATEGORY ) );
                            $term_label = $cats[0]->name;
                            ?>
                            <a href="<?php echo esc_url( $term_link ); ?>"><?php directorist_icon( $term_icon );?><?php echo esc_html( $term_label ); ?></a>
                            <?php
                            $totalTerm = count($cats);
                            if ( $totalTerm > 1 ) { $totalTerm = $totalTerm - 1; ?>
                                <div class="directorist-listing-category__popup">
                                    <span class="directorist-listing-category__extran-count">+<?php echo esc_html( $totalTerm ); ?></span>
                                    <div class="directorist-listing-category__popup__content">
                                        <?php
                                        foreach (array_slice($cats, 1) as $cat) {
                                            $term_icon  = get_term_meta( $cat->term_id, 'category_icon', true );
                                            $term_icon  = $term_icon ? $term_icon : $default_icon;
                                            $term_link  = esc_url( ATBDP_Permalink::atbdp_get_category_page( $cat ) );
                                            $term_link  = esc_url( get_term_link( $cat->term_id, ATBDP_CATEGORY ) );
                                            $term_label = $cat->name;
                                            ?>
                    
                                            <a href="<?php echo esc_url( $term_link );?>"><?php directorist_icon( $term_icon );?> <?php echo esc_html( $term_label ); ?></a>
                    
                                            <?php
                                        }
                                        ?>
                                    </div>
                    
                                </div>
                                <?php
                            }
                        }
                        else { ?>
                            <a href="#"><?php directorist_icon( $default_icon );?><?php esc_html_e('Uncategorized', 'directorist'); ?></a>
                            <?php
                        }
                        ?>
                    </div>
                        <?php

                    ATBDP()->show_static_rating(get_post($id));
                    ?>
                </div>

            </li>

        <?php endwhile; ?>

		<?php wp_reset_postdata(); ?>
    </ul>
</div>
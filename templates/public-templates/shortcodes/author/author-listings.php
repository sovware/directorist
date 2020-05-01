<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="atbd_author_listings_area">
            <?php
            if ($header_title){
            ?>
            <h1><?php _e("Author Listings", 'directorist'); ?></h1>
            <?php }
            ?>
            <?php if(!empty($author_cat_filter)) {?>
            <div class="atbd_author_filter_area">
                <?php
                /*
                 * @since 6.2.3
                 */
                do_action('atbpd_before_author_listings_category_dropdown', $all_listings);
                ?>
                <div class="atbd_dropdown">
                    <a class="atbd_dropdown-toggle" href="#"
                       id="dropdownMenuLink">
                        <?php _e("Filter by category", 'directorist'); ?> <span class="atbd_drop-caret"></span>
                    </a>

                    <div class="atbd_dropdown-menu atbd_dropdown-menu--lg" aria-labelledby="dropdownMenuLink">
                        <?php
                        foreach ($categories as $category) {
                            $active = (isset($_GET['category']) && ($category->slug == $_GET['category'])) ? 'active' : '';
                                printf('<a class="atbd_dropdown-item %s" href="%s">%s</a>', $active, add_query_arg('category', $category->slug), $category->name);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row atbd_authors_listing">
    <?php
    
    if ($listings){
        listing_view_by_grid($all_listings, $paginate, $is_disable_price);
    }else{
        // for dev
        do_action('atbdp_author_listings_html', $all_listings);
    }
    ?>

</div>
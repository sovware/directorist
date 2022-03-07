<?php
/**
 * @author  wpWax
 * @since   7.2.0
 * @version 7.2.0
 */

use Directorist\Directorist_Listings;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbdp atbdp-widget-categories">
    <?php if( 'dropdown' == $query_args['template'] ) : ?>
        <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
            <input type="hidden" name="q" placeholder="">
            <select name="in_cat" id="at_biz_dir-category" onchange="this.form.submit()">
                <?php echo $categories; ?>
            </select>
        </form>
    <?php else :
        echo $categories;
    endif; ?>
</div>


<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="directorist" class="atbd_wrapper atbd_author_profile">

    <div class="<?php Helper::directorist_container(); ?>">
        <?php
        $author->header_template();
        $author->about_template();
        $author->author_listings_template();
        ?>
    </div>
    
</div>
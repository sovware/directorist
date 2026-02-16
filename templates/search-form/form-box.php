<?php
/**
 * @author  wpWax
 * @since   7.2.2
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-form__box">

    <div class="directorist-search-form-top directorist-flex directorist-align-center directorist-search-form-inline directorist-search-form__top">
        <?php $searchform->advanced_search_form_basic_fields_template();?>
    </div>

    <?php
        $searchform->more_buttons_template();
    ?>

</div>

<div class="directorist-search-modal directorist-search-modal--advanced">
    <?php $searchform->advanced_search_form_fields_template(); ?>
</div>
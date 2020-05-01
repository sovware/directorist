<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div id="directorist" class="atbd_wrapper atbd_author_profile">
    <div class="<?php echo $container_fluid; ?>">
        <?php
        /**
         * @hooked Directorist_Template_Hooks::author_profile_header - 10
         * @hooked Directorist_Template_Hooks::author_profile_about - 15
         * @hooked Directorist_Template_Hooks::author_profile_listings - 20
         */
        do_action( 'directorist_author_profile_content' );
        ?>
    </div>
</div>

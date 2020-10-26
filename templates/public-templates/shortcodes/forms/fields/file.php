<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" id="directorist-file-upload-field">
	<?php $form->add_listing_label_template( $data );?>

	<?php require ATBDP_TEMPLATES_DIR . 'file-uploader.php'; ?>

	<?php $form->add_listing_description_template( $data ); ?>
</div>

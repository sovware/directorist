<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-searchform-basic">
	<?php
		foreach ($fields as $field) {
			$searchform->field_template( $field );
		}
	?>
</div>
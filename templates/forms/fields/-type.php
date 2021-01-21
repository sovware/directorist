<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$listing_type_count = count( $listing_types );

if ( $listing_type_count == 1 ): ?>
	<input type="hidden" name="directory_type" value="<?php echo esc_attr( $listing_types[0]->term_id ); ?>">
	<?php
else:
	?>
	<div class="atbd_content_module">
		<div class="atbd_content_module_title_area">
			<div class="atbd_area_title">
				<h4><?php esc_html_e( 'Listing Type', 'directorist' );?></h4>
			</div>
		</div>
		<div class="atbdb_content_module_contents">
			<select name="directory_type">
				<?php foreach ( $listing_types as $type ):
					?>
					<option value="<?php echo esc_attr( $type->term_id ); ?>" <?php selected( $type->term_id, $current_type, true ); ?> ><?php echo esc_attr( $type->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<?php
endif;
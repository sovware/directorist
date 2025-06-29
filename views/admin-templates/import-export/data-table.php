<?php
/**
 * @author wpWax
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns = $args['columns'];
$fields  = $args['fields'];
?>
<table class="widefat atbdp-importer-mapping-table">
    <thead>
        <tr>
            <th><?php esc_html_e('Column name', 'directorist'); ?></th>
            <th><?php esc_html_e('Map to field', 'directorist'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
		if ( is_array( $columns ) ) :
            foreach ( $columns as $column => $column_content ) :
				$field_key = directorist_translate_to_listing_field_key( $column );
				?>
                <tr>
                    <td class="atbdp-importer-mapping-table-name">
                        <p><?php echo esc_html($column ); ?></p>
                        <?php if ( ! empty( $column_content ) ) : ?>
                            <span class="description"><?php esc_html_e('Sample:', 'directorist'); ?> <code><?php echo esc_html( $column_content ); ?></code></span>
                        <?php endif; ?>
                    </td>
                    <td class="atbdp-importer-mapping-table-field">
                        <input type="hidden" name="map_from[<?php echo esc_attr( $column ); ?>]" value="<?php echo esc_attr( $column_content ); ?>" />
                        <select class="atbdp_map_to" name="<?php echo esc_attr( $column ); ?>">
                            <option value=""><?php esc_html_e( 'Do not import', 'directorist' ); ?></option>
							<optgroup label="<?php esc_attr_e( 'Listing Fields', 'directorist' ); ?>">
                            <?php foreach ( $fields as $key => $value ) : ?>
                                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $field_key ); ?>><?php echo esc_html( $value ); ?></option>
                            <?php endforeach ?>
							</optgroup>
                        </select>
                    </td>
                </tr>
            <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table>
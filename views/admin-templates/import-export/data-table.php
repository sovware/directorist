<?php
/**
 * @author wpWax
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
$headers = $args['data'];
$fields = $args['fields'];

?>
<input type="hidden" name="csv_file" value="<?php echo $args['csv_file'] ?>">
<table class="widefat atbdp-importer-mapping-table">
    <thead>
        <tr>
            <th><?php esc_html_e('Column name', 'directorist'); ?></th>
            <th><?php esc_html_e('Map to field', 'directorist'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ( is_array( $headers ) ) :
            foreach ($headers as $index => $name) : ?>
                <tr>
                    <td class="atbdp-importer-mapping-table-name">
                        <p><?php echo esc_html($index); ?></p>
                        <?php if (!empty($name)) : ?>
                            <span class="description"><?php esc_html_e('Sample:', 'directorist'); ?> <code><?php echo esc_html($name); ?></code></span>
                        <?php endif; ?>
                    </td>
                    <td class="atbdp-importer-mapping-table-field">
                        <input type="hidden" name="map_from[<?php echo esc_attr($index); ?>]" value="<?php echo esc_attr($name); ?>" />
                        <select class="atbdp_map_to" name="<?php echo esc_attr($index); ?>">
                            <option value=""><?php esc_html_e('Do not import', 'woocommerce'); ?></option>
                            <option value="">--------------</option>
                            <?php foreach ($fields as $key => $value) : ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php // selected( $mapped_value, $key ); ?>><?php echo esc_html($value); ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
            <?php 
            endforeach;
        endif;
        ?>
    </tbody>
</table>
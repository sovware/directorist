<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
$option_value = [];
foreach( $data['original_data']['options'] as $option ) {
    $key = $option['option_value'];
    if( in_array( $key, explode( ',', $value ) ) ) {
        $space = str_repeat(' ', 1);
        $option_value[] = $space . $option['option_label'];
    }
}
?>
<div class="directorist-single-info directorist-single-info-fax">
	<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
	<div class="directorist-single-info-value"><?php echo esc_html( join(',', $option_value) ); ?></div>
</div>
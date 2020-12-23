<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-single-info directorist-single-info-fax">
	<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
    <div class="directorist-single-info-value">
        <?php 
        $done = str_replace('|||', '', $value);
        $name_arr = explode('/', $done);
        $filename = end($name_arr);
        printf('<a href="%s" target="_blank" download>%s</a>', esc_url($done), $filename);
        ?>
    </div>
</div>
<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="form-group open_now">
    <label><?php esc_html_e('Open Now', 'directorist'); ?></label>
    <div class="check-btn">
        <div class="btn-checkbox">
            <label>
                <input type="checkbox" name="open_now" value="open_now" <?php if (!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) {echo "checked='checked'";} ?>>
                <span><i class="fa fa-clock-o"></i><?php esc_html_e('Open Now', 'directorist'); ?> </span>
            </label>
        </div>
    </div>
</div>
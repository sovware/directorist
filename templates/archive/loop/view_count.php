<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-view-count"><span class="<?php atbdp_icon_type(true) ?>-eye"></span><?php echo ( ! empty($listings->loop['post_view']) ) ? $listings->loop['post_view'] : 0;?></div>
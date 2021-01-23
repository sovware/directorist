<?php
  echo $args['before_widget'];
  echo '<div class="atbd_widget_title">';
  echo $args['before_title'] . esc_html(apply_filters('widget_video_title', $title)) . $args['after_title'];
  echo '</div>';
  ?>
  <div class="atbdp">
    <iframe class="embed-responsive-item" src="<?php echo $videourl; ?>" allowfullscreen></iframe>
  </div>
  <?php
  echo $args['after_widget'];
<?php
if (!atbdp_logged_in_user()) {

    $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Title', 'directorist');
    echo $args['before_widget'];
    echo '<div class="atbd_widget_title">';
    echo $args['before_title'] . esc_html(apply_filters('widget_submit_item_title', $title)) . $args['after_title'];
    echo '</div>';
    ?>
    <div class="directorist">
        <?php
        if (isset($_GET['login']) && $_GET['login'] == 'failed'){
            printf('<p class="alert-danger">  <span class="'.atbdp_icon_type().'-exclamation"></span>%s</p>',__(' Invalid username or password!', 'directorist'));
        }
        wp_login_form();
        wp_register();
        printf(__('<p>Don\'t have an account? %s</p>', 'directorist'), "<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign up', 'directorist')."</a>");
        ?>
    </div>
    <?php
    echo $args['after_widget'];
}

<div class="row">
    <div class="col-md-8">
        <div class="atbd_author_module">
            <div class="atbd_content_module">
                <div class="atbd_content_module_title_area">
                    <div class="atbd_area_title">
                        <h4>
                            <span class="<?php atbdp_icon_type(true); ?>-user"></span><?php _e('About', 'directorist'); ?>
                        </h4>
                    </div>
                </div>

                <div class="atbdb_content_module_contents">
                    <p>
                        <?php
                        echo !empty($bio) ? $content : __('Nothing to show!', 'directorist');
                        ?>
                    </p>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="widget atbd_widget">
            <div class="atbd_widget_title"><h4><?php _e('Contact Info', 'directorist'); ?></h4></div>
            <div class="atbdp atbd_author_info_widget">
                <div class="atbd_widget_contact_info">
                    <ul>
                        <?php
                        if (!empty($address)) {
                            ?>
                            <li>
                                <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span>
                                <span class="atbd_info"><?php echo !empty($address) ? esc_html($address) : ''; ?></span>
                            </li>
                            <?php
                        }
                        if (!empty($phone)) {
                            ?>
                            <!-- In Future, We will have to use a loop to print more than 1 number-->
                            <li>
                                <span class="<?php atbdp_icon_type(true); ?>-phone"></span>
                                <span class="atbd_info"><a
                                            href="tel:<?php echo esc_html(stripslashes($phone)); ?>"><?php echo esc_html(stripslashes($phone)); ?></a></span>
                            </li>
                            <?php
                        }
                        $email_show = get_directorist_option('display_author_email', 'public');
                        if ('public' === $email_show) {
                            if (!empty($email)) {
                                ?>
                                <li>
                                    <span class="<?php atbdp_icon_type(true); ?>-envelope"></span>
                                    <span class="atbd_info"><?php echo !empty($email) ? esc_html($email) : ''; ?></span>
                                </li>
                                <?php
                            }
                        } elseif ('logged_in' === $email_show) {
                            if (atbdp_logged_in_user()) {
                                if (!empty($email)) {
                                    ?>
                                    <li>
                                        <span class="<?php atbdp_icon_type(true); ?>-envelope"></span>
                                        <span class="atbd_info"><?php echo !empty($email) ? esc_html($email) : ''; ?></span>
                                    </li>
                                    <?php
                                }
                            }
                        }

                        if (!empty($website)) {
                            ?>
                            <li>
                                <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                                <span class="atbd_info"><a target="_blank" href="<?php echo !empty($website) ? esc_html($website) : ''; ?>"><?php echo !empty($website) ? esc_html($website) : ''; ?></a></span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
                if (!empty($facebook || $twitter || $linkedIn || $youtube)) {
                    ?>
                    <div class="atbd_social_wrap">
                        <?php
                        if ($facebook) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-facebook"></span></a></p>', $facebook);
                        }
                        if ($twitter) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-twitter"></span></a></p>', $twitter);
                        }
                        if ($linkedIn) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-linkedin"></span></a></p>', $linkedIn);
                        }
                        if ($youtube) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-youtube"></span></a></p>', $youtube);
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="row">
    <div class="col-md-8">
        <div class="atbd_author_module">
            <div class="atbd_content_module">
                <div class="atbd_content_module_title_area">
                    <div class="atbd_area_title">
                        <h4><span class="<?php atbdp_icon_type(true); ?>-user"></span><?php esc_html_e('About', 'directorist'); ?></h4>
                    </div>
                </div>

                <div class="atbdb_content_module_contents">
                    <p><?php echo $bio ? wp_kses_post( $bio ) : esc_html__('Nothing to show!', 'directorist');?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="widget atbd_widget">
            <div class="atbd_widget_title"><h4><?php esc_html_e('Contact Info', 'directorist'); ?></h4></div>
            <div class="atbdp atbd_author_info_widget">
                <div class="atbd_widget_contact_info">
                    <ul>
                        <?php if ($address) { ?>
                            <li>
                                <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span>
                                <span class="atbd_info"><?php echo esc_html($address); ?></span>
                            </li>
                            <?php
                        }

                        if ($phone) { ?>
                            <li>
                                <span class="<?php atbdp_icon_type(true); ?>-phone"></span>
                                <span class="atbd_info"><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $phone ); ?>"><?php echo esc_html($phone); ?></a></span>
                            </li>
                            <?php
                        }

                        if ($email_endabled && $email) { ?>
                            <li>
                                <span class="<?php atbdp_icon_type(true); ?>-envelope"></span>
                                <span class="atbd_info"><?php echo esc_html($email); ?></span>
                            </li>
                            <?php
                        }

                        if ($website) { ?>
                            <li>
                                <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                                <span class="atbd_info"><a target="_blank" href="<?php echo esc_url($website); ?>"><?php echo esc_html($website); ?></a></span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
                if ($facebook || $twitter || $linkedIn || $youtube) {
                    ?>
                    <div class="atbd_social_wrap">
                        <?php
                        if ($facebook) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-facebook"></span></a></p>', esc_url($facebook));
                        }
                        if ($twitter) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-twitter"></span></a></p>', esc_url($twitter));
                        }
                        if ($linkedIn) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-linkedin"></span></a></p>', esc_url($linkedIn));
                        }
                        if ($youtube) {
                            printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-youtube"></span></a></p>', esc_url($youtube));
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
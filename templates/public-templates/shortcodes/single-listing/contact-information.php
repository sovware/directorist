<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

if ((!$hide_contact_info) && !empty($address || $phone ||$phone2 ||$fax || $email || $website || $zip || $social) && empty($disable_contact_info)) { ?>
    <div class="atbd_content_module atbd_contact_information_module">
        <div class="atbd_content_module_title_area">
            <div class="atbd_area_title">
                <h4>
                    <span class="<?php atbdp_icon_type(true);?>-envelope-o"></span><?php echo esc_html( $contact_info_text ); ?>
                </h4>
            </div>
        </div>
        <div class="atbdb_content_module_contents">
            <div class="atbd_contact_info">
                <ul>
                    <?php
                    if (!empty($address) && !empty($display_address_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-map-marker"></span><?php echo esc_html( $address_label ); ?></div>
                            <div class="atbd_info"><?php echo $address_text; ?></div>
                        </li>
                        <?php
                    }

                    if (isset($phone) && !is_empty_v($phone) && !empty($display_phone_field) && $plan_phone) { ?>
                        <li>
                            <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-phone"></span><?php echo esc_html( $phone_label ); ?></div>
                            <div class="atbd_info">
                                <a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $phone ); ?>"><?php ATBDP_Helper::sanitize_html( $phone ); ?></a>
                            </div>
                        </li>
                        <?php
                    }

                    if (isset($phone2) && !is_empty_v($phone2) && !empty($display_phone2_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-phone"></span><?php echo esc_html( $phone_label2 ); ?></div>
                            <div class="atbd_info"><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $phone2 ); ?>"><?php ATBDP_Helper::sanitize_html( $phone2 ); ?></a>
                            </div>
                        </li>
                        <?php
                    }

                    if (isset($fax) && !is_empty_v($fax) && !empty($display_fax_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-fax"></span><?php echo esc_html( $fax_label ); ?></div>
                            <div class="atbd_info"><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $fax ); ?>"><?php ATBDP_Helper::sanitize_html( $fax ); ?></a>
                            </div>
                        </li>
                        <?php
                    }

                    if (!empty($email) && !empty($display_email_field) && $plan_email) { ?>
                        <li>
                            <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-envelope"></span><?php echo esc_html($email_label ); ?></div>
                            <div class="atbd_info"><a target="_top" href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a></div>
                        </li>
                        <?php
                    }

                    if (!empty($website) && !empty($display_website_field) && $plan_webLink) { ?>
                        <li>
                            <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-globe"></span><?php echo esc_html( $website_label ); ?></div>
                            <a target="_blank" href="<?php echo esc_url($website); ?>" class="atbd_info" <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($website); ?></a>
                        </li>
                        <?php
                    }

                    if (isset($zip) && !is_empty_v($zip) && !empty($display_zip_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-at"></span><?php echo esc_html( $zip_label ); ?></div>
                            <div class="atbd_info"><?php echo esc_html($zip); ?></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <?php
            if (!empty($social) && is_array($social) && !empty($display_social_info_field) && $plan_social_networks) { ?>
                <div class="atbd_director_social_wrap">
                    <?php foreach ($social as $link) { ?>
                        <a target='_blank' href="<?php echo esc_url($link['url']); ?>" class="<?php echo esc_attr($link['id']); ?>">
                            <span class="fa fa-<?php echo esc_attr($link['id']); ?>"></span>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}
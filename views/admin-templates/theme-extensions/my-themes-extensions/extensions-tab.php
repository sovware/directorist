<?php $outdated_plugins_key = array_keys($args['outdated_plugin_list']); ?>

<div id="atbdp-extensions-tab" class="ext-wrapper et-contents__tab-item atbdp-tab__content active">
    <div class="ext-installed">
        <h4>Installed</h4>
        <div class="ext-installed-table">
            <?php if ( ! empty( $args['installed_extension_list'] ) ) : ?>
                <div class="ext-table-responsive">
                    <form id="atbdp-my-extensions-form" class="atbdp-my-extensions-form" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4">
                                        <div class="ei-action-wrapper">
                                            <div class="ei-select-all">
                                                <div class="directorist-checkbox directorist-checkbox-success">
                                                    <input type="checkbox" name="select-all-installed" id="select-all-installed">
                                                    <label class="directorist-checkbox__label" for="select-all-installed" ></label>
                                                </div>
                                            </div>
                                            <div class="ei-action-dropdown">
                                                <select id="bulk-actions" name="bulk-actions">
                                                    <option value="">Bulk Action</option>
                                                    <option value="activate">Activate</option>
                                                    <option value="deactivate">Deactivate</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="ei-action-btn">Apply</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($args['installed_extension_list'] as $extension_base => $extension) : ?>
                                    <tr>
                                        <td>
                                            <div class="extension-name">
                                                <div class="directorist-checkbox directorist-checkbox-success">
                                                    <input type="checkbox" id="<?php echo esc_attr( $extension_base ); ?>" name="<?php echo esc_attr( $extension_base ); ?>" class="extension-name-checkbox">
                                                    <label class="directorist-checkbox__label" for="<?php echo esc_attr( $extension_base ); ?>">
                                                        <?php

                                                            $ext_key       = preg_replace( '/\/.+/', '', $extension_base );
                                                            $ext_key_alias = $args['ATBDP_Extensions']->get_extension_alias_key( $ext_key );;
                                                            $img           = 'https://via.placeholder.com/44' ;

                                                            if ( ! empty( $args[ 'extension_list' ][ $ext_key ] ) ) {
                                                                $img = $args['extension_list'][$ext_key]['thumbnail'];
                                                            } else if ( ! empty( $args[ 'extension_list' ][ $ext_key_alias ] ) ) {
                                                                $img = $args['extension_list'][$ext_key_alias]['thumbnail'];
                                                            }
                                                        ?>
                                                        <img src="<?php echo esc_url( $img ); ?>" alt="" width="44" height="44">
                                                        <span class="ext-text">
                                                            <?php echo esc_html( $extension['Name'] ); ?>
                                                            <span class="ext-version">v<?php echo esc_html( $extension['Version'] ); ?></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="directorist_status-badge">
                                            <?php if (is_plugin_active($extension_base)) : ?>
                                                <span class="active-badge">Active</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="directorist_ext-update">
                                            <?php if (in_array($extension_base, $outdated_plugins_key)) : ?>
                                                <p class="ext-update-info">Update available <!-- <span>What's new?</span></p> -->
                                            <?php else : ?>
                                                <p class="atbdp-text-muted atbdp-mb-0">Up to date</p>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="ext-action">
                                                <?php if (in_array($extension_base, $outdated_plugins_key)) : ?>
                                                    <a href="#" class="ext-update-btn ext-action-btn" data-key="<?php echo esc_attr( $extension_base ); ?>">Update</a>
                                                <?php endif; ?>
                                                <a href="<?php echo esc_url( $args['settings_url'] ); ?>" class="ext-action-btn"><i class="la la-settings"></i> Settings</a>
                                                <div>
                                                    <a href="" class="ext-action-drop"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="ext-action-drop__item">
                                                        <a href="#" class="ext-action-link ext-action-uninstall" data-target="<?php echo esc_attr( $extension_base ); ?>">Uninstall</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            <?php else : ?>
                <p class="atbdp-text-center"><?php esc_html_e('No extension found') ?></p>
            <?php endif; ?>
        </div>
    </div><!-- ends: .ext-installed -->

    <?php if (!empty($args['extensions_available_in_subscriptions'])) : ?>
        <div class="ext-available">
            <h4><?php esc_html_e('Available in your subscription (' . count( array_keys( $args['extensions_available_in_subscriptions'] ) ) .')', 'directorist')  ?></h4>
            <div class="ext-available-table">
                <div class="ext-table-responsive">
                    <form id="atbdp-my-subscribed-extensions-form" class="atbdp-my-subscribed-extensions-form" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4">
                                        <div class="ei-action-wrapper">
                                            <div class="ei-select-all">
                                                <div class="directorist-checkbox directorist-checkbox-success">
                                                    <input type="checkbox" name="select-all-subscription" id="select-all-subscription">
                                                    <label class="directorist-checkbox__label" for="select-all-subscription"></label>
                                                </div>
                                            </div>
                                            <div class="ei-action-dropdown">
                                                <select id="bulk-actions" name="bulk-actions">
                                                    <option value="">Bulk Action</option>
                                                    <option value="activate">Install</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="ei-action-btn">Apply</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($args['extensions_available_in_subscriptions'] as $extension_base => $extension) : ?>
                                    <?php $extension_base_alias = $args['ATBDP_Extensions']->get_extension_alias_key( $extension_base ); ?>
                                    <tr>
                                        <td>
                                            <div class="extension-name">
                                                <div class="directorist-checkbox directorist-checkbox-success">
                                                    <input type="checkbox" id="<?php echo esc_attr( $extension_base ); ?>" name="<?php echo esc_attr( $extension_base ); ?>" class="extension-name-checkbox">
                                                    <label class="directorist-checkbox__label" for="<?php echo esc_attr( $extension_base ); ?>">
                                                        <?php
                                                            $img = ! empty( $extension['thumbnail'] ) ? $extension['thumbnail'] : DIRECTORIST_ASSETS . 'images/no-image.png';
                                                        ?>
                                                        <img src="<?php echo esc_url( $img ); ?>" width="44" height="44" alt=""><?php echo esc_html( $extension['title'] ) ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="ext-info">
                                                <?php
                                                if (!empty($args['extension_list'][$extension_base])) {
                                                    esc_html_e($args['extension_list'][$extension_base]['description'], 'directorust');
                                                } else if (!empty($args['extension_list'][$extension_base_alias])) {
                                                    esc_html_e($args['extension_list'][$extension_base_alias]['description'], 'directorust');
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="ext-action ext-action-<?php echo esc_attr( $extension_base ); ?>">
                                                <a href="#" class="file-install-btn ext-action-btn" data-type="plugin" data-key="<?php echo esc_attr( $extension_base ); ?>">
                                                    <i class="la la-download"></i> <?php esc_html_e('Install', 'directorist') ?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div><!-- ends: .ext-available -->
    <?php endif; ?>

    <?php if (!empty($args['required_extensions_list'])) : ?>
        <div class="ext-available">
            <h4 class="req-ext-title"><?php esc_html_e('Required Extensions (' . count( array_keys( $args['required_extensions_list'] ) ) .')', 'directorist')  ?></h4>
            <div class="ext-available-table">
                <div class="ext-table-responsive">
                    <form id="atbdp-required-extensions-form" class="atbdp-my-required-extensions-form" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4">
                                        <div class="ei-action-wrapper">
                                            <div class="ei-select-all">
                                                <div class="directorist-checkbox directorist-checkbox-success">
                                                    <input type="checkbox" name="select-all" id="select-all-required-extensions">
                                                    <label class="directorist-checkbox__label" for="select-all-required-extensions"></label>
                                                </div>
                                            </div>
                                            <div class="ei-action-dropdown">
                                                <select name="bulk-actions">
                                                    <option value="">Bulk Action</option>
                                                    <option value="install">Install</option>
                                                    <option value="activate">Activate</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="ei-action-btn">Apply</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $args['required_extensions_list'] as $extension_base => $extension) : ?>
                                <?php $extension_base_alias = $args['ATBDP_Extensions']->get_extension_alias_key( $extension_base ); ?>
                                    <tr>
                                        <td>
                                            <div class="extension-name">
                                                <div class="directorist-checkbox directorist-checkbox-success">
                                                    <?php if ( $extension[ 'installed' ] ) : ?>
                                                    <input type="checkbox" id="<?php echo 'required_' . esc_attr( $extension_base ); ?>" name="<?php echo esc_attr( $extension_base ); ?>" value="<?php echo esc_attr( $extension[ 'base'] ); ?>" class="extension-name-checkbox extension-activate-checkbox">
                                                    <?php elseif ( $extension[ 'purchased' ] ) : ?>
                                                    <input type="checkbox" id="<?php echo 'required_' . esc_attr( $extension_base ); ?>" name="<?php echo esc_attr( $extension_base ); ?>" value="<?php echo esc_attr( $extension_base ); ?>" class="extension-name-checkbox extension-install-checkbox">
                                                    <?php endif; ?>
                                                    
                                                    <label for="<?php echo 'required_' . esc_attr( $extension_base ); ?>" class="directorist-checkbox__label">
                                                        <?php
                                                            
                                                            $ext_name = ( isset( $args['extension_list'][$extension_base] ) ) ? $args['extension_list'][$extension_base]['name'] : '';
                                                            
                                                            if ( empty( $ext_name ) ) {
                                                                $ext_name = ( isset( $args['extension_list'][$extension_base_alias] ) ) ? $args['extension_list'][$extension_base_alias]['name'] : '';
                                                            }
                                                            
                                                            $img = 'https://via.placeholder.com/44';
                                                            if ( ! empty( $args['extension_list'][$extension_base] ) ) {
                                                                $img = $args['extension_list'][$extension_base]['thumbnail'];
                                                            } else if ( ! empty( $args['extension_list'][$extension_base_alias] ) ) {
                                                                $img = $args['extension_list'][$extension_base_alias]['thumbnail'];
                                                            }

                                                            echo "<img src='". esc_url( $img ) . "' width='44' height='44' alt=''>";
                                                            echo esc_html( $ext_name );
                                                        ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="ext-info">
                                                <?php
                                                    if ( ! empty($args['extension_list'][$extension_base])) {
                                                        esc_html_e($args['extension_list'][$extension_base]['description'], 'directorust');
                                                    }
                                                    else if ( ! empty($args['extension_list'][$extension_base_alias])) {
                                                        esc_html_e($args['extension_list'][$extension_base_alias]['description'], 'directorust');
                                                    }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="ext-action ext-action-<?php echo esc_attr( $extension_base ); ?>">
                                                <?php if ( $extension[ 'installed' ] ) : ?>
                                                <a href="#" class="plugin-active-btn ext-action-btn" data-type="plugin" data-key="<?php echo esc_attr( $extension[ 'base' ] ); ?>">
                                                    <?php esc_html_e('Activate', 'directorist') ?>
                                                </a>
                                                <?php elseif ( $extension[ 'purchased' ] ) : ?>
                                                <a href="#" class="file-install-btn ext-action-btn" data-type="plugin" data-key="<?php echo esc_attr( $extension_base ); ?>">
                                                    <?php esc_html_e('Install', 'directorist') ?>
                                                </a>
                                                <?php else: 
                                                    $download_link = ( ! empty( $args['extension_list'][$extension_base] ) ) ? $args['extension_list'][$extension_base]['link'] : '';
                                                
                                                    if ( empty( $download_link ) ) {
                                                        $download_link = ( ! empty( $args['extension_list'][$extension_base_alias] ) ) ? $args['extension_list'][$extension_base_alias]['link'] : ''; 
                                                    }
                                                ?>
                                                <a href="<?php echo esc_url( $download_link ); ?>" class="ext-action-btn" target="_blank">
                                                    <i class="la la-download"></i> <?php esc_html_e('Get Now', 'directorist') ?>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div><!-- ends: .ext-available -->
    <?php endif; ?>
</div><!-- ends: .ext-wrapper -->
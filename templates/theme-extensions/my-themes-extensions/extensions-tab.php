<?php $outdated_plugins_key = array_keys($args['outdated_plugins']); ?>
<div id="atbdp-extensions-tab" class="ext-wrapper et-contents__tab-item atbdp-tab__content active">
    <div class="ext-installed">
        <h4>Installed</h4>
        <div class="ext-installed-table">
            <?php if ( ! empty( $args['installed_extensions'] ) ) : ?>
                <div class="ext-table-responsive">
                    <form id="atbdp-my-extensions-form" class="atbdp-my-extensions-form" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4">
                                        <div class="ei-action-wrapper">
                                            <div class="ei-select-all"><input type="checkbox" name="select-all" id=""></div>
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
                                <?php foreach ($args['installed_extensions'] as $extension_base => $extension) : ?>
                                    <tr>
                                        <td>
                                            <div class="extension-name">
                                                <input type="checkbox" id="<?php echo $extension_base; ?>" name="<?php echo $extension_base; ?>" class="extension-name-checkbox">
                                                <label for="<?php echo $extension_base; ?>">
                                                    <img src="https://via.placeholder.com/44" alt="">
                                                    <?php echo $extension['Name'] ?>
                                                    <span class="ext-version">v<?php echo $extension['Version'] ?></span>
                                                </label>
                                            </div>
                                        </td>

                                        <td>
                                            <?php if (is_plugin_active($extension_base)) : ?>
                                                <span class="active-badge">Active</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if (in_array($extension_base, $outdated_plugins_key)) : ?>
                                                <p class="ext-update-info">Update available <!-- <span>What's new?</span></p> -->
                                            <?php else : ?>
                                                <p class="atbdp-text-muted atbdp-mb-0">Up to date</p>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="ext-action">
                                                <?php if (in_array($extension_base, $outdated_plugins_key)) : ?>
                                                    <a href="#" class="ext-update-btn ext-action-btn" data-key="<?php echo $extension_base; ?>">Update</a>
                                                <?php endif; ?>
                                                <a href="<?php echo $args['settings_url'] ?>" class="ext-action-btn"><i class="la la-settings"></i> Settings</a>
                                                <div>
                                                    <a href="" class="ext-action-drop"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="ext-action-drop__item">
                                                        <a href="#" class="ext-action-link ext-action-uninstall" data-target="<?php echo $extension_base; ?>">Uninstall</a>
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
                <p class="atbdp-text-center"><?php _e('No extension found') ?></p>
            <?php endif; ?>
        </div>
    </div><!-- ends: .ext-installed -->

    <?php if (!empty($args['plugins_available_in_subscriptions'])) : ?>
        <div class="ext-available">
            <h4><?php _e('Available in your subscription', 'directorist')  ?></h4>
            <div class="ext-available-table">
                <div class="ext-table-responsive">
                    <form id="atbdp-my-subscribed-extensions-form" class="atbdp-my-subscribed-extensions-form" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4">
                                        <div class="ei-action-wrapper">
                                            <div class="ei-select-all"><input type="checkbox" name="select-all" id=""></div>
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
                                <?php foreach ($args['plugins_available_in_subscriptions'] as $extension_base => $extension) : ?>
                                    <tr>
                                        <td>
                                            <div class="extension-name">
                                                <input type="checkbox" id="<?php echo $extension_base; ?>" name="<?php echo $extension_base; ?>" class="extension-name-checkbox">
                                                <label for="<?php echo $extension_base; ?>">
                                                    <?php
                                                        $img = 'https://via.placeholder.com/44';
                                                        if (!empty($args['extension_list'][$extension_base])) {
                                                            $img = $args['extension_list'][$extension_base]['thumbnail'];
                                                        }
                                                    ?>
                                                    <img src="<?php echo $img; ?>" width="44" height="44" alt=""><?php echo $extension['title'] ?>
                                                </label>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="ext-info">
                                                <?php
                                                if (!empty($args['extension_list'][$extension_base])) {
                                                    _e($args['extension_list'][$extension_base]['description'], 'directorust');
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="ext-action ext-action-<?php echo $extension_base; ?>">
                                                <a href="#" class="file-install-btn ext-action-btn" data-type="plugin" data-key="<?php echo $extension_base ?>">
                                                    <i class="la la-download"></i> <?php _e('Install', 'directorist') ?>
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
</div><!-- ends: .ext-wrapper -->
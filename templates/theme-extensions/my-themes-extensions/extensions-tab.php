<?php 
    // var_dump( $args['outdated_plugins'] );
    $outdated_plugins_key = array_keys( $args['outdated_plugins'] );
    // var_dump( $outdated_plugins_key );
    // var_dump( $outdated_plugins_key );
?>
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
                            <?php foreach( $args['installed_extensions'] as $extension_base => $extension ) : ?>
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
                                        <?php if ( is_plugin_active( $extension_base ) ) : ?>
                                            <span class="active-badge">Active</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                    <?php if ( in_array( $extension_base, $outdated_plugins_key ) ) : ?>
                                        <p class="ext-update-info">Update available <span>What's new?</span></p>
                                    <?php else: ?>
                                        <p class="atbdp-text-muted atbdp-mb-0">Up to date</p>
                                    <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <div class="ext-action">
                                            <?php if ( in_array( $extension_base, $outdated_plugins_key ) ) : ?>
                                            <a href="#" class="ext-update-btn ext-action-btn">Update</a>
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
            <?php else: ?>
                <p class="atbdp-text-center"><?php _e( 'No extension found' ) ?></p>
            <?php endif; ?>
        </div>
    </div><!-- ends: .ext-installed -->
    <div class="ext-available atbdp-d-none">
        <h4>Available in your subscription</h4>
        <div class="ext-available-table">
            <div class="ext-table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th colspan="4">
                                <div class="ei-action-wrapper">
                                    <div class="ei-select-all"><input type="checkbox" name="select-all" id=""></div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="extension-name">
                                    <input type="checkbox" id="item-1">
                                    <label for="item-1"><img src="https://via.placeholder.com/44" alt=""> Rank Featured Listings</label>
                                </div>
                            </td>
                            <td><span class="ext-info">It is a coupon generating tool that can increase conversions and make your directory site more fascinating.</span></td>
                            <td>
                                <div class="ext-action">
                                    <a href="" class="ext-install-btn ext-action-btn"><i class="la la-download"></i> Install</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="extension-name">
                                    <input type="checkbox" id="item-1">
                                    <label for="item-1"><img src="https://via.placeholder.com/44" alt=""> Directorist Coupon</label>
                                </div>
                            </td>
                            <td><span class="ext-info">It is a coupon generating tool that can increase conversions and make your directory site more fascinating.</span></td>
                            <td>
                                <div class="ext-action">
                                    <a href="" class="ext-install-btn ext-action-btn"><i class="la la-download"></i> Install</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="extension-name">
                                    <input type="checkbox" id="item-1">
                                    <label for="item-1"><img src="https://via.placeholder.com/44" alt=""> Directorist Coupon</label>
                                </div>
                            </td>
                            <td><span class="ext-info">It is a coupon generating tool that can increase conversions and make your directory site more fascinating.</span></td>
                            <td>
                                <div class="ext-action">
                                    <a href="" class="ext-install-btn ext-action-btn"><i class="la la-download"></i> Install</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- ends: .ext-available -->
</div><!-- ends: .ext-wrapper -->
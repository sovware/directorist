<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div id="directorist" class="directorist atbd_wrapper dashboard_area">

    <?php
    /**
     * @hooked Directorist_Listing_Dashboard > alert_message_template - 10
     */
    do_action( 'directorist_dashboard_before_container' );
    ?>

    <div class="<?php echo esc_attr( $container_fluid ); ?>">
        <?php
        /**
         * @since 6.6
         * @hooked Directorist_Listing_Dashboard > section_title - 10
         */
        do_action( 'directorist_dashboard_title_area', $display_title );
        ?>
        <div class="atbd-dashboard-nav-toggle-icon">
            <a href="" class="atbd-dashboard-nav-toggler"><i class="la la-bars"></i></a>
        </div>
        <div class="atbd_dashboard_wrapper atbd_tab">
            <div class="atbd_user_dashboard_nav atbd_tab_nav">

                <?php
                /**
                 * @since 6.6
                 * @hooked Directorist_Listing_Dashboard > nav_tabs_template - 10
                 * @hooked Directorist_Listing_Dashboard > nav_buttons_template - 15
                 */
                do_action( 'directorist_dashboard_navigation');
                ?>

            </div>

            <div class="atbd_tab-content">

                <?php
                /**
                 * @since 6.6
                 * @hooked Directorist_Listing_Dashboard > tab_contents_html - 10
                 */
                do_action( 'directorist_dashboard_tab_contents');
                ?>

            </div>
        </div>
    </div>
</div>

<div class="atbdp-userDashboard-area">
    <div class="atbdp-userDashboard-tab">
        <div class="atbd_tab_nav">
            <ul>
                <li class="atbdp_tab_nav--content-link">
                    <a href="#" target="all_llistings" class="atbd_tn_link tabItemActive">All Listings</a>
                </li>
                <li class="atbdp_tab_nav--content-link">
                    <a href="#" target="published" class="atbd_tn_link">Published</a>
                </li>
                <li class="atbdp_tab_nav--content-link">
                    <a href="#" target="pending" class="atbd_tn_link">Pending</a>
                </li>
                <li class="atbdp_tab_nav--content-link">
                    <a href="#" target="unpaid" class="atbd_tn_link">UnPaid</a>
                </li>
                <li class="atbdp_tab_nav--content-link">
                    <a href="#" target="expired" class="atbd_tn_link">Expired</a>
                </li>
            </ul>
            <div class="atbdp-userDashboard-search">
                <div class="atbdp-userDashboard-search__icon">
                    <i class="la la-search"></i>
                </div>
                <input type="text" placeholder="Search listings">
            </div>
        </div>
        <div class="atbdp-userDashboard-tabcontent">
            <div class="atbd_tab_inner tabContentActive" id="all_llistings">
                <div class="atbdp-listing-table">
                    <table class="atbdp-table atbdp-table-responsive">
                        <thead>
                            <tr>
                                <th>Listings</th>
                                <th>Plan</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge success">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge success">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge success">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="atbdp-pagination">
                        <ul>
                            <li><a href="#" class="prev"><i class="la la-arrow-left"></i></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#" class="next"><i class="la la-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="atbd_tab_inner" id="published">
            <div class="atbdp-listing-table">
                    <table class="atbdp-table atbdp-table-responsive">
                        <thead>
                            <tr>
                                <th>Listings</th>
                                <th>Plan</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge primary">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge primary">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge primary">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="atbdp-pagination">
                        <ul>
                            <li><a href="#" class="prev"><i class="la la-arrow-left"></i></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#" class="next"><i class="la la-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="atbd_tab_inner" id="pending">
            <div class="atbdp-listing-table">
                    <table class="atbdp-table atbdp-table-responsive">
                        <thead>
                            <tr>
                                <th>Listings</th>
                                <th>Plan</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge warning">Pending</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge warning">Pending</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge warning">Pending</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="atbdp-pagination">
                        <ul>
                            <li><a href="#" class="prev"><i class="la la-arrow-left"></i></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#" class="next"><i class="la la-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="atbd_tab_inner" id="unpaid">
            <div class="atbdp-listing-table">
                    <table class="atbdp-table atbdp-table-responsive">
                        <thead>
                            <tr>
                                <th>Listings</th>
                                <th>Plan</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge danger">Expired</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge danger">Expired</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge danger">Expired</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="atbdp-pagination">
                        <ul>
                            <li><a href="#" class="prev"><i class="la la-arrow-left"></i></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#" class="next"><i class="la la-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="atbd_tab_inner" id="expired">
            <div class="atbdp-listing-table">
                    <table class="atbdp-table atbdp-table-responsive">
                        <thead>
                            <tr>
                                <th>Listings</th>
                                <th>Plan</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge success">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge success">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="atbdp-listing-info">
                                        <div class="atbdp-listing-info__img">
                                            <img src="http://directoristpn.local/wp-content/uploads/2020/12/architecture-2586504_1920-300x200-1.jpg" alt="">
                                        </div>
                                        <div class="atbdp-listing-info__content">
                                            <h4 class="atbdp-title">Gravenhurst Cottage</h4>
                                            <span class="atbd-price">$275.20</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="atbdp-listing-plan">Super Plan</span>
                                </td>
                                <td>
                                    <span class="atbdp-ex-plan">March 24, 2020</span>
                                </td>
                                <td>
                                    <span class="atbdp-badge dashboard-badge success">Publish</span>
                                </td>
                                <td>
                                    <div class="atbdp-actions">
                                        <a href="#" class="atbdp-link-btn"><i class="la la-edit"></i>Edit</a>
                                        <div class="atbdp-dropdown">
                                            <a href="#" class="atbdp-btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                            <div class="atbdp-dropdown-menu">
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                    <a class="atbdp-dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                        <label for="m-navigation">
                                                            Mark as Negotiation
                                                        </label>
                                                    </div>
                                                    <div class="atbdp-custom-checkbox">
                                                        <input type="checkbox" id="m-sold" name="mark-sold">
                                                        <label for="m-sold">
                                                            Mark as Sold
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="atbdp-dropdown-menu__list">
                                                    <a class="atbdp-dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="atbdp-pagination">
                        <ul>
                            <li><a href="#" class="prev"><i class="la la-arrow-left"></i></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#" class="next"><i class="la la-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * @package Directorist
 * @since 5.9.3
 */
do_action('atbdp_after_user_dashboard');
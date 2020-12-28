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
        <div class="directorist_userDashboard-area">
            <div class="directorist_userDashboard-tab">
                <div class="atbd_tab_nav">
                    <ul>
                        <li class="directorist_tab_nav--content-link">
                            <a href="#" target="all_llistings" class="atbd_tn_link tabItemActive">All Listings</a>
                        </li>
                        <li class="directorist_tab_nav--content-link">
                            <a href="#" target="published" class="atbd_tn_link">Published</a>
                        </li>
                        <li class="directorist_tab_nav--content-link">
                            <a href="#" target="pending" class="atbd_tn_link">Pending</a>
                        </li>
                        <li class="directorist_tab_nav--content-link">
                            <a href="#" target="unpaid" class="atbd_tn_link">UnPaid</a>
                        </li>
                        <li class="directorist_tab_nav--content-link">
                            <a href="#" target="expired" class="atbd_tn_link">Expired</a>
                        </li>
                    </ul>
                    <div class="directorist_userDashboard-search">
                        <div class="directorist_userDashboard-search__icon">
                            <i class="la la-search"></i>
                        </div>
                        <input type="text" placeholder="Search listings">
                    </div>
                </div>
                <div class="directorist_userDashboard-tabcontent">
                    <div class="atbd_tab_inner tabContentActive" id="all_llistings">
                        <div class="directorist_listing-table directorist_table-responsive">
                            <table class="directorist_table">
                                <thead>
                                    <tr>
                                        <th class="directorist_table-listing">Listings</th>
                                        <th class="directorist_table-plan">Plan</th>
                                        <th class="directorist_table-ex-date">Expiration Date</th>
                                        <th class="directorist_table-status">Status</th>
                                        <th class="directorist_table-actions"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge success">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class=""></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-amazon-pay"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge success">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge success">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="directorist_pagination">
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
                        <div class="directorist_listing-table directorist_table-responsive">
                            <table class="directorist_table">
                                <thead>
                                    <tr>
                                        <th class="directorist_table-listing">Listings</th>
                                        <th class="directorist_table-plan">Plan</th>
                                        <th class="directorist_table-ex-date">Expiration Date</th>
                                        <th class="directorist_table-status">Status</th>
                                        <th class="directorist_table-actions"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge primary">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge primary">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge primary">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="directorist_pagination">
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
                        <div class="directorist_listing-table directorist_table-responsive">
                            <table class="directorist_table">
                                <thead>
                                    <tr>
                                        <th class="directorist_table-listing">Listings</th>
                                        <th class="directorist_table-plan">Plan</th>
                                        <th class="directorist_table-ex-date">Expiration Date</th>
                                        <th class="directorist_table-status">Status</th>
                                        <th class="directorist_table-actions"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge warning">Pending</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge warning">Pending</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge warning">Pending</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="directorist_pagination">
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
                        <div class="directorist_listing-table directorist_table-responsive">
                            <table class="directorist_table">
                                <thead>
                                    <tr>
                                        <th class="directorist_table-listing">Listings</th>
                                        <th class="directorist_table-plan">Plan</th>
                                        <th class="directorist_table-ex-date">Expiration Date</th>
                                        <th class="directorist_table-status">Status</th>
                                        <th class="directorist_table-actions"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge danger">Expired</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge danger">Expired</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge danger">Expired</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="directorist_pagination">
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
                        <div class="directorist_listing-table directorist_table-responsive">
                            <table class="directorist_table">
                                <thead>
                                    <tr>
                                        <th class="directorist_table-listing">Listings</th>
                                        <th class="directorist_table-plan">Plan</th>
                                        <th class="directorist_table-ex-date">Expiration Date</th>
                                        <th class="directorist_table-status">Status</th>
                                        <th class="directorist_table-actions"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge success">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge success">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="directorist_listing-info">
                                                <div class="directorist_listing-info__img">
                                                    <img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
                                                </div>
                                                <div class="directorist_listing-info__content">
                                                    <h4 class="directorist_title">Gravenhurst Cottage</h4>
                                                    <span class="directorist_price">$275.20</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directorist_listing-plan">Super Plan</span>
                                        </td>
                                        <td>
                                            <span class="directorist_ex-plan">March 24, 2020</span>
                                        </td>
                                        <td>
                                            <span class="directorist_badge dashboard-badge success">Publish</span>
                                        </td>
                                        <td>
                                            <div class="directorist_actions">
                                                <a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
                                                <div class="directorist_dropdown">
                                                    <a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
                                                    <div class="directorist_dropdown-menu">
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-money-bill-wave"></i>Change Plan</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="lab la-adversal"></i>Promote Listing</a>
                                                            <a class="directorist_dropdown-item" href="#"><i class="las la-hand-holding-usd"></i>Pay Now</a>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-navigation" name="mark-navigation">
                                                                <label for="m-navigation">
                                                                    Mark as Negotiation
                                                                </label>
                                                            </div>
                                                            <div class="directorist_custom-checkbox">
                                                                <input type="checkbox" id="m-sold" name="mark-sold">
                                                                <label for="m-sold">
                                                                    Mark as Sold
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="directorist_dropdown-menu__list">
                                                            <a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="directorist_pagination">
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
    </div>
</div>



<?php
/**
 * @package Directorist
 * @since 5.9.3
 */
do_action('directorist_after_user_dashboard');
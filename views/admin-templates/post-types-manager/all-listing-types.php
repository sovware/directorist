<?php $show_migration_button = apply_filters( 'directorist_show_migration_button', false ); ?>

<div class="wrap">
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">

    <div class="directorist_builder-wrap">
        <?php
            /**
             * Fires before all directory types table
             * @since 7.2.0
             */
            do_action( 'directorist_before_all_directory_types' );
        ?>
        <div class="directorist_builder-header">
            <div class="directorist_builder-header__left">
                <div class="directorist_logo">
                    <img src="https://directorist.com/wp-content/uploads/2020/08/directorist_logo.png" alt="">
                </div>
            </div>
            <div class="directorist_builder-header__right">
                <ul class="directorist_builder-links">
                    <li>
                        <a href="https://directorist.com/documentation/" target="_blank">
                            <i class="la la-file"></i>
                            <span class="link-text"><?php esc_html_e( 'Documentation', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/dashboard/#support" target="_blank">
                            <i class="la la-question-circle"></i>
                            <span class="link-text"><?php esc_html_e( 'Support', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/contact/" target="_blank">
                            <i class="la la-star"></i>
                            <span class="link-text"><?php esc_html_e( 'Feedback', 'directorist' ); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="directorist_builder-body">
            <h2 class="directorist_builder__title"><?php esc_html_e( 'All Directory Types', 'directorist' ); ?></h2>
            <div class="directorist_builder__content">
                <div class="directorist_builder__content__left">
                    <button class="directorist_link-block directorist_link-block-primary cptm-modal-toggle" data-target="cptm-create-directory-modal">
                        <span class="directorist_link-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"><path d="M13.125 6.125h-5.25V.875a.875.875 0 0 0-1.75 0v5.25H.875a.875.875 0 0 0 0 1.75h5.25v5.25a.875.875 0 0 0 1.75 0v-5.25h5.25a.875.875 0 0 0 0-1.75Z" fill="#fff" fill-rule="evenodd" data-name="Path 868"/></svg>
                        </span>
                        <span class="directorist_link-text"><?php esc_html_e( 'Add New Directory Type', 'directorist' ); ?></span>
                    </button>

                    <button class="directorist_link-block directorist_link-block-success directorist_btn-import cptm-modal-toggle" data-target="cptm-import-directory-modal">
                        <span class="directorist_link-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="472 154 14 14"><path d="M485.3 162.4a.7.7 0 0 0-.7.7v2.8a.7.7 0 0 1-.7.7h-9.8a.7.7 0 0 1-.7-.7v-2.8a.7.7 0 1 0-1.4 0v2.8c0 1.16.94 2.1 2.1 2.1h9.8a2.1 2.1 0 0 0 2.1-2.1v-2.8a.7.7 0 0 0-.7-.7Zm-6.797 1.197a.7.7 0 0 0 .231.147c.17.075.363.075.532 0a.7.7 0 0 0 .231-.147l2.8-2.8a.703.703 0 0 0-.994-.994l-1.603 1.61V154.7a.7.7 0 1 0-1.4 0v6.713l-1.603-1.61a.703.703 0 0 0-.994.994l2.8 2.8Z" fill="#fff" fill-rule="evenodd" data-name="Path 1663"/></svg>
                        </span>
                        <span class="directorist_link-text">
                            <?php esc_html_e( 'Import Directory Type', 'directorist' ) ?>
                        </span>
                    </button>

                    <?php if ( $show_migration_button ) : ?>
                    <button class="directorist_link-block directorist_link-block-success directorist_btn-migrate cptm-modal-toggle" data-target="cptm-directory-mirgation-modal">
                        <span class="directorist_link-icon">
                            <i class="la la-download"></i>
                        </span>
                        <span class="directorist_link-text">
                            <?php esc_html_e( 'Migrate', 'directorist' ) ?>
                        </span>
                    </button>
                    <?php endif; ?>
                </div>
                <?php
                    $all_items =  wp_count_terms('atbdp_listing_types');
                    $listing_types = get_terms([
                       'taxonomy'   => 'atbdp_listing_types',
                       'hide_empty' => false,
                       'orderby'    => 'date',
                       'order'      => 'DSCE',
                    ]);
                ?>
                <div class="directorist_builder__content__right">
                    <div class="directorist_builder--tab directorist-tab">
                        <div class="directorist-tab__nav">
                            <ul class="directorist-tab__nav__items">
                                <li class="directorist-tab__nav__item">
                                    <a href="#" target="directory-type__all" class="directorist-tab__nav__link directorist-tab__nav__active"><?php esc_html_e( 'All','directorist' ); ?><span class="directorist_count">(<?php echo esc_attr( ! empty( $all_items ) ? $all_items : 0 ); ?>)</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="directorist-tab__content">
                            <div class="directorist-tab__pane directorist-tab__pane--active" id="directory-type__all">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title"><?php esc_html_e( 'Title', 'directorist' ); ?></th>
                                                <th class="directorist_listing-slug"><?php esc_html_e( 'Slug', 'directorist' ); ?></th>
                                                <th class="directorist_listing-count"><span class="directorist_listing-count-title"><?php esc_html_e( 'Listings', 'directorist' ); ?></span></th>
                                                <th class="directorist_listing-c-date"><?php esc_html_e( 'Created Date', 'directorist' ); ?></th>
                                                <th class="directorist_listing-c-action"><?php esc_html_e( 'Action', 'directorist' ); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if( $listing_types ) {
                                                foreach( $listing_types as $listing_type) {
                                                    $default = get_term_meta( $listing_type->term_id, '_default', true );
                                                    $edit_link = admin_url('edit.php' . '?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=' . absint( $listing_type->term_id ) . '&action=edit');
                                                    $delete_link = admin_url('admin-post.php' . '?listing_type_id=' . absint( $listing_type->term_id ) . '&action=delete_listing_type');
                                                    $delete_link = wp_nonce_url( $delete_link, 'delete_listing_type');
                                                    $created_time = get_term_meta( $listing_type->term_id, '_created_date', true );
                                            ?>
                                            <tr class="directory-type-row" data-term-id="<?php echo esc_attr( $listing_type->term_id ); ?>">
                                                <td>
                                                    <a href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>" class="directorist_title">
                                                        <?php echo esc_html( ! empty( $listing_type->name ) ? $listing_type->name : '-' ); ?>
                                                        <?php if( $default ) { ?>
                                                        <span class="directorist_badge"><?php esc_html_e( 'Default', 'directorist' ); ?></span>
                                                        <?php } ?>
                                                    </a>
                                                    <span class="directorist_listing-id">ID: #<?php echo esc_attr( ! empty( $listing_type->term_id ) ? $listing_type->term_id : '' ); ?></span>
                                                </td>
                                                <td class="directorist-type-slug">
                                                    <div class="directorist-type-slug-content">
                                                        <span class="directorist_listing-slug-text directorist-slug-text-<?php echo esc_attr( $listing_type->term_id ); ?>" data-value="<?php echo esc_attr( ! empty( $listing_type->slug ) ? $listing_type->slug : '-' ); ?>" contenteditable="false">
                                                            <?php echo esc_html( html_entity_decode( $listing_type->slug ) ); ?>
                                                        </span>
                                                        <div class="directorist-listing-slug-edit-wrap">
                                                            <a href="" class="directorist-listing-slug__edit" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="" class="directorist_listing-slug-formText-add" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="#" class="directorist_listing-slug-formText-remove directorist_listing-slug-formText-remove--hidden"></a>
                                                        </div>
                                                    </div>
                                                    <p class='directorist-slug-notice directorist-slug-notice-<?php echo esc_attr( $listing_type->term_id ); ?>'></p>
                                                </td>
                                                <td class="directorist-type-count">
                                                    <span class="directorist_listing-count"><?php echo esc_html( $listing_type->count ); ?></span>
                                                </td>
                                                <td><?php
                                                if( $created_time ) {
                                                    echo esc_attr( date( 'F j, Y', $created_time ) );
                                                }
                                                ?></td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>" class="directorist_btn directorist_btn-primary"><i class="fa fa-edit"></i><?php esc_html_e( 'Edit', 'directorist' ); ?></a>
                                                        <?php
                                                        if( ! $default ) {  ?>
                                                            <div class="directorist_more-dropdown">
                                                                <a href="#" class="directorist_more-dropdown-toggle">
                                                                    <i class="fa fa-ellipsis-h"></i>
                                                                </a>
                                                                <div class="directorist_more-dropdown-option">
                                                                    <ul>
                                                                        <li>
                                                                            <a href="#">
                                                                                <div data-type-id="<?php echo absint( $listing_type->term_id ); ?>" class="directorist_listing-type-checkbox directorist_custom-checkbox submitdefault">
                                                                                    <input class="submitDefaultCheckbox" type="checkbox" name="check-1" id="check-1">
                                                                                    <label for="check-1">
                                                                                        <span class="checkbox-text">
                                                                                        <?php esc_html_e( 'Make It Default', 'directorist' ); ?>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" class="cptm-modal-toggle atbdp-directory-delete-link-action" data-delete-link="<?php echo esc_url( $delete_link ); ?>" data-target="cptm-delete-directory-modal">
                                                                                <i class="fa fa-trash"></i><?php esc_html_e( 'Delete', 'directorist' ); ?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="directorist_notifier"></div>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="directorist-tab__pane" id="directory-type__published">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title"><?php esc_html_e( 'Title', 'directorist' ); ?></th>
                                                <th class="directorist_listing-slug"><?php esc_html_e( 'Slug', 'directorist' ); ?></th>
                                                <th class="directorist_listing-count"><span class="directorist_listing-count-title"><?php esc_html_e( 'Listings', 'directorist' ); ?></span></th>
                                                <th class="directorist_listing-c-date"><?php esc_html_e( 'Created Date', 'directorist' ); ?></th>
                                                <th class="directorist_listing-c-action"><?php esc_html_e( 'Action', 'directorist' ); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="directory-type-row" data-term-id="<?php echo esc_attr( $listing_type->term_id ); ?>">
                                                <td>
                                                    <a href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>" class="directorist_title">
                                                        Published Directory 1
                                                        <?php if( $default ) { ?>
                                                        <span class="directorist_badge"><?php esc_html_e( 'Published', 'directorist' ); ?></span>
                                                        <?php } ?>
                                                    </a>
                                                    <span class="directorist_listing-id">ID: #<?php echo esc_attr( ! empty( $listing_type->term_id ) ? $listing_type->term_id : '' ); ?></span>
                                                </td>
                                                <td class="directorist-type-slug">
                                                    <div class="directorist-type-slug-content">
                                                        <span class="directorist_listing-slug-text directorist-slug-text-<?php echo esc_attr( $listing_type->term_id ); ?>" data-value="<?php echo esc_attr( ! empty( $listing_type->slug ) ? $listing_type->slug : '-' ); ?>" contenteditable="false">
                                                            <?php echo esc_html( html_entity_decode( $listing_type->slug ) ); ?>
                                                        </span>
                                                        <div class="directorist-listing-slug-edit-wrap">
                                                            <a href="" class="directorist-listing-slug__edit" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="" class="directorist_listing-slug-formText-add" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="#" class="directorist_listing-slug-formText-remove directorist_listing-slug-formText-remove--hidden"></a>
                                                        </div>
                                                    </div>
                                                    <p class='directorist-slug-notice directorist-slug-notice-<?php echo esc_attr( $listing_type->term_id ); ?>'></p>
                                                </td>
                                                <td class="directorist-type-count">
                                                    <span class="directorist_listing-count"><?php echo esc_html( $listing_type->count ); ?></span>
                                                </td>
                                                <td><?php
                                                if( $created_time ) {
                                                    echo esc_attr( date( 'F j, Y', $created_time ) );
                                                }
                                                ?></td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="directorist_btn directorist_btn-primary"><i class="fa fa-edit"></i><?php esc_html_e( 'Edit', 'directorist' ); ?></a>
                                                        <?php
                                                        if( ! $default ) {  ?>
                                                            <div class="directorist_more-dropdown">
                                                                <a href="#" class="directorist_more-dropdown-toggle">
                                                                    <i class="fa fa-ellipsis-h"></i>
                                                                </a>
                                                                <div class="directorist_more-dropdown-option">
                                                                    <ul>
                                                                        <li>
                                                                            <a href="#">
                                                                                <div data-type-id="1" class="directorist_listing-type-checkbox directorist_custom-checkbox submitdefault">
                                                                                    <input class="submitDefaultCheckbox" type="checkbox" name="check-1" id="check-1">
                                                                                    <label for="check-1">
                                                                                        <span class="checkbox-text">
                                                                                        <?php esc_html_e( 'Make It Default', 'directorist' ); ?>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" class="cptm-modal-toggle atbdp-directory-delete-link-action" data-delete-link="<?php echo esc_url( $delete_link ); ?>" data-target="cptm-delete-directory-modal">
                                                                                <i class="fa fa-trash"></i><?php esc_html_e( 'Delete', 'directorist' ); ?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="directorist_notifier"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="directorist-tab__pane" id="directory-type__private">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title"><?php esc_html_e( 'Title', 'directorist' ); ?></th>
                                                <th class="directorist_listing-slug"><?php esc_html_e( 'Slug', 'directorist' ); ?></th>
                                                <th class="directorist_listing-count"><span class="directorist_listing-count-title"><?php esc_html_e( 'Listings', 'directorist' ); ?></span></th>
                                                <th class="directorist_listing-c-date"><?php esc_html_e( 'Created Date', 'directorist' ); ?></th>
                                                <th class="directorist_listing-c-action"><?php esc_html_e( 'Action', 'directorist' ); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="directory-type-row" data-term-id="<?php echo esc_attr( $listing_type->term_id ); ?>">
                                                <td>
                                                    <a href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>" class="directorist_title">
                                                        Private Directory 1
                                                        <?php if( $default ) { ?>
                                                        <span class="directorist_badge"><?php esc_html_e( 'Private', 'directorist' ); ?></span>
                                                        <?php } ?>
                                                    </a>
                                                    <span class="directorist_listing-id">ID: #<?php echo esc_attr( ! empty( $listing_type->term_id ) ? $listing_type->term_id : '' ); ?></span>
                                                </td>
                                                <td class="directorist-type-slug">
                                                    <div class="directorist-type-slug-content">
                                                        <span class="directorist_listing-slug-text directorist-slug-text-<?php echo esc_attr( $listing_type->term_id ); ?>" data-value="<?php echo esc_attr( ! empty( $listing_type->slug ) ? $listing_type->slug : '-' ); ?>" contenteditable="false">
                                                            <?php echo esc_html( html_entity_decode( $listing_type->slug ) ); ?>
                                                        </span>
                                                        <div class="directorist-listing-slug-edit-wrap">
                                                            <a href="" class="directorist-listing-slug__edit" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="" class="directorist_listing-slug-formText-add" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="#" class="directorist_listing-slug-formText-remove directorist_listing-slug-formText-remove--hidden"></a>
                                                        </div>
                                                    </div>
                                                    <p class='directorist-slug-notice directorist-slug-notice-<?php echo esc_attr( $listing_type->term_id ); ?>'></p>
                                                </td>
                                                <td class="directorist-type-count">
                                                    <span class="directorist_listing-count"><?php echo esc_html( $listing_type->count ); ?></span>
                                                </td>
                                                <td><?php
                                                if( $created_time ) {
                                                    echo esc_attr( date( 'F j, Y', $created_time ) );
                                                }
                                                ?></td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="directorist_btn directorist_btn-primary"><i class="fa fa-edit"></i><?php esc_html_e( 'Edit', 'directorist' ); ?></a>
                                                        <?php
                                                        if( ! $default ) {  ?>
                                                            <div class="directorist_more-dropdown">
                                                                <a href="#" class="directorist_more-dropdown-toggle">
                                                                    <i class="fa fa-ellipsis-h"></i>
                                                                </a>
                                                                <div class="directorist_more-dropdown-option">
                                                                    <ul>
                                                                        <li>
                                                                            <a href="#">
                                                                                <div data-type-id="1" class="directorist_listing-type-checkbox directorist_custom-checkbox submitdefault">
                                                                                    <input class="submitDefaultCheckbox" type="checkbox" name="check-1" id="check-1">
                                                                                    <label for="check-1">
                                                                                        <span class="checkbox-text">
                                                                                        <?php esc_html_e( 'Make It Default', 'directorist' ); ?>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" class="cptm-modal-toggle atbdp-directory-delete-link-action" data-delete-link="<?php echo esc_url( $delete_link ); ?>" data-target="cptm-delete-directory-modal">
                                                                                <i class="fa fa-trash"></i><?php esc_html_e( 'Delete', 'directorist' ); ?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="directorist_notifier"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Import Directory -->
<div class="cptm-modal-container cptm-create-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header cptm-create-directory-modal__header">
                    <div class="cptm-create-directory-modal__header__content">
                        <h3 class="cptm-modal-header-title cptm-create-directory-modal__title"><?php esc_html_e( 'Add New Directory Type', 'directorist' ); ?></h3>
                        <p class="cptm-create-directory-modal__desc"><?php esc_html_e( 'Start from scratch or select a template to save time', 'directorist' ); ?></p>
                    </div>
                    <button class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-create-directory-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11.998"><path d="m7.409 5.998 4.295-4.286A1.003 1.003 0 1 0 10.286.294L6 4.59 1.714.294A1.003 1.003 0 0 0 .296 1.712L4.59 5.998.296 10.284a.999.999 0 0 0 0 1.419.999.999 0 0 0 1.418 0L6 7.407l4.286 4.296a.999.999 0 0 0 1.418 0 .999.999 0 0 0 0-1.419L7.41 5.998Z" fill="#3c3c3c" fill-rule="evenodd" data-name="times"/></svg>
                    </button>
                </div>
                         
                <div class="cptm-modal-body cptm-center-content cptm-content-wide cptm-create-directory-modal__body">
                    <div class="cptm-create-directory-modal__action">
                        <a href="<?php echo admin_url( 'admin.php?page=templatiq' ) ?>" class="cptm-create-directory-modal__action__single <?php echo ! is_plugin_active( 'templatiq/templatiq.php' ) ? 'directorist_directory_template_library' : ''; ?>">
                            <span class="modal-btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="510 399 30 30"><path d="M536.667 399h-23.334a3.333 3.333 0 0 0-3.333 3.333V409a3.333 3.333 0 0 0 3.333 3.333h23.334A3.333 3.333 0 0 0 540 409v-6.667a3.333 3.333 0 0 0-3.333-3.333Zm-23.334 10v-6.667h23.334V409h-23.334Zm10 5h-10a3.333 3.333 0 0 0-3.333 3.333v8.334a3.333 3.333 0 0 0 3.333 3.333h10a3.333 3.333 0 0 0 3.334-3.333v-8.334a3.333 3.333 0 0 0-3.334-3.333Zm-10 11.667v-8.334h10v8.334h-10ZM536.667 414h-5a3.333 3.333 0 0 0-3.334 3.333v8.334a3.333 3.333 0 0 0 3.334 3.333h5a3.333 3.333 0 0 0 3.333-3.333v-8.334a3.333 3.333 0 0 0-3.333-3.333Zm-5 11.667v-8.334h5v8.334h-5Z" fill="#3e62f5" fill-rule="evenodd" data-name="template"/></svg>
                            </span>
                            <span class="modal-btn-text"><?php esc_html_e( 'Select a directory template', 'directorist' ); ?></span>
                            <span><?php echo esc_html( 'Templatiq required', 'directorist' ); ?></span>
                        </a>
                        <a href="<?php echo esc_attr( $data['add_new_link'] ); ?>" class="cptm-create-directory-modal__action__single">
                            <span class="modal-btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="740 399 30 30"><g data-name="Group 2618"><path d="m766.212 409.354-.937.936a10.781 10.781 0 0 0-3.712-.65c-6.035.011-10.925 4.897-10.936 10.927 0 1.298.225 2.548.65 3.71l-1.526 1.524-3.313-3.31-3.238-3.235.1-.1 15.66-15.636.689-.687 3.162 3.147 3.388 3.359.013.015ZM742.68 420.5l-2.627 7.237a.96.96 0 0 0 .225.987c.261.253.642.34.987.225l7.242-2.624-5.827-5.825Zm25.92-20.152c-.887-.875-2.062-1.312-3.286-1.35a4.633 4.633 0 0 0-3.263 1.387l-1.526 1.557.008.005 3.163 3.148 3.386 3.356.005.008 1.55-1.581a4.562 4.562 0 0 0 1.337-3.235v-.05a4.522 4.522 0 0 0-1.374-3.245Z" fill="#3e62f5" fill-rule="evenodd" data-name="Path 1478"/><path d="M761.563 412.125c-4.652 0-8.437 3.785-8.437 8.437 0 4.651 3.785 8.437 8.437 8.437 4.652 0 8.437-3.786 8.437-8.437 0-4.652-3.785-8.437-8.437-8.437ZM765 421.81h-2.188v2.188a1.25 1.25 0 0 1-2.501 0v-2.188h-2.188a1.25 1.25 0 0 1 0-2.5h2.188v-2.189a1.25 1.25 0 0 1 2.5 0v2.188H765a1.25 1.25 0 1 1 0 2.501Z" fill="#3e62f5" fill-rule="evenodd" data-name="Path 1479"/></g></svg>
                            </span>
                            <span class="modal-btn-text"><?php esc_html_e( 'Create from scratch', 'directorist' ); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Import Directory -->
<div class="cptm-modal-container cptm-import-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Import', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-import-directory-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-import-directory-form">
                        <div class="cptm-form-group cptm-mb-10">
                            <input type="text" name="directory-name" class="cptm-form-control cptm-text-center cptm-form-field" placeholder="Directory Name or ID">
                            <div class="cptm-file-input-wrap">
                                <label for="directory-import-file" class="cptm-btn cptm-btn-secondery"><?php esc_html_e( 'Select File', 'directorist' ); ?></label>
                                <button type="submit" class="cptm-btn cptm-btn-primary">
                                    <span class="cptm-loading-icon cptm-d-none">
                                        <span class="fa fa-spin fa fa-spinner"></span>
                                    </span>
                                    <?php esc_html_e( 'Import', 'directorist' ); ?>
                                </button>
                                <input id="directory-import-file" name="directory-import-file" type="file" accept=".json" class="cptm-d-none cptm-form-field cptm-file-field">
                            </div>

                            <p class="cptm-info-text">
                                <b><?php esc_html_e( 'Note:', 'directorist' ); ?></b>
                                <?php esc_html_e( 'You can use an existed directory ID to update it the importing file', 'directorist' ); ?>
                            </p>
                        </div>

                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>
                    </form>
                </div>
            </div>

            <div class="cptm-section-alert-area cptm-import-directory-modal-alert cptm-d-none">
                <div class="cptm-section-alert-content">
                    <div class="cptm-section-alert-icon cptm-alert-success">
                        <span class="fa fa-check"></span>
                    </div>

                    <div class="cptm-section-alert-message">
                        <?php esc_html_e( 'The directory has been imported successfuly, redirecting...', 'directorist' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Delete Directory -->
<div class="cptm-modal-container cptm-delete-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Delete Derectory', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-delete-directory-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-import-directory-form">
                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <h2 class="cptm-title-2 cptm-text-center"><?php esc_html_e( 'Are you sure?', 'directorist' ) ?></h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondary cptm-modal-toggle atbdp-directory-delete-cancel-link" data-target="cptm-delete-directory-modal">
                                <?php esc_html_e( 'Cancel', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-danger atbdp-directory-delete-link">
                                <?php esc_html_e( 'Delete', 'directorist' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
;

if ( $show_migration_button ) : ?>
<!-- Model : Migration -->
<div class="cptm-modal-container cptm-directory-mirgation-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Migrate', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-directory-mirgation-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-directory-migration-form">
                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <h2 class="cptm-title-2 cptm-text-center cptm-comfirmation-text">
                            <?php esc_html_e( 'Are you sure?', 'directorist' ) ?>
                        </h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondery cptm-modal-toggle atbdp-directory-migration-cencel-link" data-target="cptm-directory-mirgation-modal">
                                <?php esc_html_e( 'No', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-primary atbdp-directory-migration-link">
                                <?php esc_html_e( 'Yes', 'directorist' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
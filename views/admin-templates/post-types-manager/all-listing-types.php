<?php $show_migration_button = apply_filters( 'directorist_show_migration_button', false ); ?>

<div class="wrap">
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">

    <div class="directorist_builder-wrap">
        <div class="directorist_builder-header">
            <div class="directorist_builder-header__left">
                <div class="directorist_logo">
                    <img src="https://directorist.com/wp-content/uploads/2020/08/directorist_logo.png" alt="">
                </div>
            </div>
            <div class="directorist_builder-header__right">
                <ul class="directorist_builder-links">
                    <li>
                        <a href="https://directorist.com/documentation/">
                            <i class="la la-file"></i>
                            <span class="link-text"><?php _e( 'Documentation', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/dashboard/#support">
                            <i class="la la-question-circle"></i>
                            <span class="link-text"><?php _e( 'Support', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/contact/">
                            <i class="la la-star"></i>
                            <span class="link-text"><?php _e( 'Feedback', 'directorist' ); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="directorist_builder-body">
            <h2 class="directorist_builder__title"><?php _e( 'All Directory Types', 'directorist' ); ?></h2>
            <div class="directorist_builder__content">
                <div class="directorist_builder__content--left">
                    <a href="<?php echo esc_attr( $data['add_new_link'] ); ?>" class="directorist_link-block directorist_link-block-primary">
                        <span class="directorist_link-icon">
                            <i class="la la-plus"></i>
                        </span>
                        <span class="directorist_link-text"><?php _e( 'Create New Directory Type', 'directorist' ); ?></span>
                    </a>

                    <a href="#" class="directorist_link-block directorist_link-block-success directorist_btn-import cptm-modal-toggle" data-target="cptm-import-directory-modal">
                        <span class="directorist_link-icon">
                            <i class="la la-download"></i>
                        </span>
                        <span class="directorist_link-text">
                            <?php _e( 'Import', 'directorist' ) ?>
                        </span>
                    </a>
                
                    <?php if ( $show_migration_button ) : ?>
                    <a href="#" class="directorist_link-block directorist_link-block-success directorist_btn-migrate cptm-modal-toggle" data-target="cptm-directory-mirgation-modal">
                        <span class="directorist_link-icon">
                            <i class="la la-download"></i>
                        </span>
                        <span class="directorist_link-text">
                            <?php _e( 'Migrate', 'directorist' ) ?>
                        </span>
                    </a>
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
                <div class="directorist_builder__content--right">
                    <div class="directorist_builder--tab">
                        <div class="atbd_tab_nav">
                            <ul>
                                <li class="directorist_builder--tab-item">
                                    <a href="#" target="all" class="atbd_tn_link tabItemActive"><?php _e( 'All','directorist' ); ?><span class="directorist_count">(<?php echo ! empty( $all_items ) ? $all_items : 0; ?>)</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="directorist_builder--tabContent">
                            <div class="atbd_tab_inner tabContentActive" id="all">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title"><?php _e( 'Title', 'directorist' ); ?></th>
                                                <th class="directorist_listing-slug"><?php _e( 'Slug', 'directorist' ); ?></th>
                                                <th class="directorist_listing-count"><span class="directorist_listing-count-title"><?php _e( 'Listings', 'directorist' ); ?></span></th>
                                                <th class="directorist_listing-c-date"><?php _e( 'Created Date', 'directorist' ); ?></th>
                                                <th class="directorist_listing-c-action"><?php _e( 'Action', 'directorist' ); ?></th>
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
                                            <tr>
                                                <td>
                                                    <a href="<?php echo ! empty( $edit_link ) ? $edit_link : '#'; ?>" class="directorist_title">
                                                        <?php echo ! empty( $listing_type->name ) ? $listing_type->name : '-'; ?>
                                                        <?php if( $default ) { ?>
                                                        <span class="directorist_badge"><?php _e( 'Default', 'directorist' ); ?></span>
                                                        <?php } ?>
                                                    </a>
                                                    <span class="directorist_listing-id">ID: #<?php echo ! empty( $listing_type->term_id ) ? $listing_type->term_id : ''; ?></span>
                                                </td>
                                                <td>
                                                    <span class="directorist_listing-slug-text directorist-slug-text-<?php echo $listing_type->term_id; ?>"><?php echo $listing_type->slug; ?></span>
                                                    <div class="directorist-listing-slug-edit-wrap">
                                                        <a href="" class="directorist-listing-slug__edit">
                                                            <i class="la la-edit"></i>
                                                        </a>
                                                        <div class="directorist-listing-slug__form">
                                                            <form action="#">
                                                                <div class="directorist_listing-slug__form--inline">
                                                                    <div class="directorist_listing-slug__form--input directorist-form-group">
                                                                        <input type="text" class="directorist-form-element directorist-type-slug-<?php echo $listing_type->term_id; ?>" name="directorist-slug-input" value="<?php echo ! empty( $listing_type->slug ) ? $listing_type->slug : '-'; ?>">
                                                                    </div>
                                                                    <div class="directorist_listing-slug__form--action">
                                                                        <a href="#" class="directorist_listing-slug-formText-add" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"><i class="la la-check"></i></a>
                                                                        <a href="#" class="directorist_listing-slug-formText-remove"><i class="la la-times"></i></a>
                                                                    </div>
                                                                    <div class="directorist_listing-slug__form--loader"></div>
                                                                </div>
                                                            </form>
                                                            <p class='directorist-slug-notice directorist-slug-notice-<?php echo $listing_type->term_id; ?>'></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="directorist_listing-count"><?php echo $listing_type->count; ?></span></td>
                                                <td><?php
                                                if( $created_time ) {
                                                    echo date( 'F j, Y', $created_time );
                                                }
                                                ?></td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="<?php echo ! empty( $edit_link ) ? $edit_link : '#'; ?>" class="directorist_btn directorist_btn-primary"><i class="la la-edit"></i><?php _e( 'Edit', 'directorist' ); ?></a>    
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
                                                                                        <?php _e( 'Make It Default', 'directorist' ); ?>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" class="cptm-modal-toggle atbdp-directory-delete-link-action" data-delete-link="<?php echo $delete_link; ?>" data-target="cptm-delete-directory-modal">
                                                                                <i class="la la-trash"></i><?php _e( 'Delete', 'directorist' ); ?>
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
                        </div>
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
                    <h3 class="cptm-modal-header-title"><?php _e( 'Import', 'directorist' ); ?></h3>
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
                            <p class="cptm-info-text">
                                <?php _e( '<b>Note:</b> You can use an existed directory ID to update it the importing file', 'directorist' ) ?>
                            </p>
                        </div>

                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <div class="cptm-file-input-wrap">
                            <label for="directory-import-file" class="cptm-btn cptm-btn-secondery"><?php _e( 'Select File', 'directorist' ); ?></label>
                            <button type="submit" class="cptm-btn cptm-btn-primary">
                                <span class="cptm-loading-icon cptm-d-none">
                                    <span class="fa fa-spin fa fa-spinner"></span>
                                </span>
                                <?php _e( 'Import', 'directorist' ); ?>
                            </button>
                            <input id="directory-import-file" name="directory-import-file" type="file" accept=".json" class="cptm-d-none cptm-form-field cptm-file-field">
                        </div>
                    </form>
                </div>
            </div>

            <div class="cptm-section-alert-area cptm-import-directory-modal-alert cptm-d-none">
                <div class="cptm-section-alert-content">
                    <div class="cptm-section-alert-icon cptm-alert-success">
                        <span class="fa fa-check"></span>
                    </div>

                    <div class="cptm-section-alert-message">
                        <?php _e( 'The directory has been imported successfuly, redirecting...', 'directorist' ); ?>
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
                    <h3 class="cptm-modal-header-title"><?php _e( 'Delete Derectory', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-delete-directory-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-import-directory-form">
                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <h2 class="cptm-title-2 cptm-text-center"><?php _e( 'Are you sure?', 'directorist' ) ?></h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondary cptm-modal-toggle atbdp-directory-delete-cancel-link" data-target="cptm-delete-directory-modal">
                                <?php _e( 'Cancel', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-danger atbdp-directory-delete-link">
                                <?php _e( 'Delete', 'directorist' ); ?>
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
                    <h3 class="cptm-modal-header-title"><?php _e( 'Migrate', 'directorist' ); ?></h3>
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
                            <?php _e( 'Are you sure?', 'directorist' ) ?>
                        </h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondery cptm-modal-toggle atbdp-directory-migration-cencel-link" data-target="cptm-directory-mirgation-modal">
                                <?php _e( 'No', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-primary atbdp-directory-migration-link">
                                <?php _e( 'Yes', 'directorist' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
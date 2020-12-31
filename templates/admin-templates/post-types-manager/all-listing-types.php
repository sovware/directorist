<div class="wrap">
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">
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
                                <input type="text" name="directory-name" class="cptm-form-control cptm-text-center cptm-form-field" placeholder="Directory Name">
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
                    <a href="<?php echo esc_attr( $data['add_new_link'] ); ?>" class="directorist_link-block">
                        <span class="directorist_link-icon">
                            <i class="la la-plus"></i>
                        </span>
                        <span class="directorist_link-text"><?php _e( 'Create New Directory Type', 'directorist' ); ?></span>
                    </a>
                    <a href="#" class="directorist_btn directorist_btn-success directorist_btn-import cptm-modal-toggle" data-target="cptm-import-directory-modal">
                        <span class="directorist_link-icon">
                            <i class="la la-download"></i>
                        </span>
                        <span class="directorist_link-text">
                            <?php _e( 'Import', 'directorist' ) ?>
                        </span>
                    </a>
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
                                                    $default_type = $default ? '<span class="page-title-action directorist_badge directorist_badge-primary">Default</span>' : '';
                                                    $edit_link   = admin_url('edit.php' . '?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=' . absint( $listing_type->term_id ) . '&action=edit');
                                                    $delete_link = admin_url('admin-post.php' . '?listing_type_id=' . absint( $listing_type->term_id ) . '&action=delete_listing_type');
                                                    $delete_link = wp_nonce_url( $delete_link, 'delete_listing_type');
                                                    $created_time = get_term_meta( $listing_type->term_id, '_created_date', true );             
                                                    if( ! $default ){
                                                        $actions['default'] = '<a href="" data-type-id="'. absint( $listing_type->term_id ) .'" class="submitdefault">Make Default</a>';
                                                      }

                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo ! empty( $edit_link ) ? $edit_link : '#'; ?>" class="directorist_title"><?php echo ! empty( $listing_type->name ) ? $listing_type->name . $default_type : '-'; ?></a>
                                                </td>
                                                <td><span class="directorist_listing-count"><?php echo $listing_type->count; ?></span></td>
                                                <td><?php 
                                                if( $created_time ) {
                                                    echo date( 'F j, Y', $created_time );
                                                }
                                                ?></td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <?php  
                                                        if( ! $default ) {
                                                        ?>
                                                        <a href="<?php echo ! empty( $edit_link ) ? $edit_link : '#'; ?>" data-type-id="<?php echo absint( $listing_type->term_id ); ?>" class="directorist_btn directorist_btn-primary submitdefault"><i class="la la-edit"></i><?php _e( 'Mark as Default', 'directorist' ); ?></a>
                                                        <?php } ?>
                                                        <a href="<?php echo ! empty( $edit_link ) ? $edit_link : '#'; ?>" class="directorist_btn directorist_btn-primary"><i class="la la-edit"></i><?php _e( 'Edit', 'directorist' ); ?></a>
                                                        <a href="<?php echo ! empty( $delete_link ) ? $delete_link : '#'; ?>" class="directorist_btn directorist_btn-danger"><i class="la la-trash"></i><?php _e( 'Delete', 'directorist' ); ?></a>
                                                        
                                                        <div class="directorist_listing-type-checkbox directorist_custom-checkbox">
                                                            <input type="checkbox" name="check-1" id="check-1">
                                                            <label for="check-1">
                                                                <span class="checkbox-text">
                                                                    Make It Default
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
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
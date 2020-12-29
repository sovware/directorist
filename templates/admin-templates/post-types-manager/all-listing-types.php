<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( 'Directory Types', 'directorist' ) ?></h1>
    <a href="<?php echo esc_attr( $data['add_new_link'] ); ?>" class="page-title-action"><?php _e( 'Add new directory', 'directorist' ) ?></a>
    <a href="#" data-target="cptm-import-directory-modal" class="page-title-action cptm-modal-toggle"><?php _e( 'Import', 'directorist' ) ?></a>
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">

    <form method="GET"> 
        <?php $data['post_types_list_table']->display() ?>
    </form>

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
                    <img src="https://demo.jsnorm.com/react/strikingdash/static/media/Logo_Dark.9ef25a33.svg" alt="">
                </div>
            </div>
            <div class="directorist_builder-header__right">
                <ul class="directorist_builder-links">
                    <li>
                        <a href="#">
                            <i class="la la-file"></i>
                            <span class="link-text">Documentation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="la la-question-circle"></i>
                            <span class="link-text">Support</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="la la-star"></i>
                            <span class="link-text">Feedback</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="directorist_builder-body">
            <h2 class="directorist_builder__title">All Listing Types</h2>
            <div class="directorist_builder__content">
                <div class="directorist_builder__content--left">
                    <a href="#" class="directorist_link-block">
                        <span class="directorist_link-icon">
                            <i class="la la-plus"></i>
                        </span>
                        <span class="directorist_link-text">Create New Listing Type</span>
                    </a>
                </div>
                <div class="directorist_builder__content--right">
                    <div class="directorist_builder--tab">
                        <div class="atbd_tab_nav">
                            <ul>
                                <li class="directorist_builder--tab-item">
                                    <a href="#" target="all" class="atbd_tn_link tabItemActive">All <span class="directorist_count">(12)</span></a>
                                </li>
                                <li class="directorist_builder--tab-item">
                                    <a href="#" target="published" class="atbd_tn_link">Published<span class="directorist_count">(12)</a>
                                </li>
                                <li class="directorist_builder--tab-item">
                                    <a href="#" target="private" class="atbd_tn_link">Private<span class="directorist_count">(12)</a>
                                </li>
                            </ul>
                        </div>
                        <div class="directorist_builder--tabContent">
                            <div class="atbd_tab_inner tabContentActive" id="all">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title">Title</th>
                                                <th class="directorist_listing-count">Listings</th>
                                                <th class="directorist_listing-c-date">Created Date</th>
                                                <th class="directorist_listing-c-action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="atbd_tab_inner" id="published">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title">Title</th>
                                                <th class="directorist_listing-count">Listings</th>
                                                <th class="directorist_listing-c-date">Created Date</th>
                                                <th class="directorist_listing-c-action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="atbd_tab_inner" id="private">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title">Title</th>
                                                <th class="directorist_listing-count">Listings</th>
                                                <th class="directorist_listing-c-date">Created Date</th>
                                                <th class="directorist_listing-c-action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="directorist_title">Place</a>
                                                </td>
                                                <td>62</td>
                                                <td>August 30, 2020</td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="#" class="btn btn-primary"><i class="la la-edit"></i>Edit</a>
                                                        <a href="#" class="btn btn-danger"><i class="la la-trash"></i>Delete</a>
                                                    </div>
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
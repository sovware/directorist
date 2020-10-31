<div class="wrap">
    <h1 class="wp-heading-inline">Listing Types</h1>
    <a href="<?php echo esc_attr( $data['add_new_link'] ); ?>" class="page-title-action">Add Listing Type</a>
    <a href="#" data-target="cptm-import-directory-modal" class="page-title-action cptm-modal-toggle">Import</a>
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">

    <form method="GET"> 
        <?php $data['post_types_list_table']->display() ?>
    </form>

    <div class="cptm-modal-container cptm-import-directory-modal">
        <div class="cptm-modal-wrap">
            <div class="cptm-modal">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title">Import</h3>
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

                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10">
                            
                        </div>

                        <div class="cptm-file-input-wrap">
                            <label for="directory-import-file" class="cptm-btn cptm-btn-secondery">Select File</label>
                            <button type="submit" class="cptm-btn cptm-btn-primary">Import</button>
                            <input id="directory-import-file" name="directory-import-file" type="file" accept=".json" class="cptm-d-none cptm-form-field cptm-file-field">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
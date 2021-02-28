<?php 
    $warnings      = directorist_warnings();
    $_count        = count( $warnings );
    $warning_count = ! empty( $_count ) ?  '<span class="directorist-badge directorist-badge-warning">' . $_count . '</span>' : '';
?>
<section class="atbds_wrapper">
    <div class="row">
        <div class="col-12">
            <h2 class="atbds_title"><?php _e( 'System Status', 'directorist' ); ?></h2>
        </div>
    </div>
    <div class="atbds_row">
        <div class="atbds_col-left">
            <aside class="atbds_sidebar pl-30">
                <ul class="nav" class="atbds_status-nav" id="atbds_status-tab" role="tablist">
                    <li class="nav-item">
                        <a href="#atbds_system-info" class="nav-link active" data-action="atbds_tab" data-tabArea="atbds_system-status-tab"><?php _e( 'System Information', 'directorist' ); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#atbds_warning" class="nav-link" data-action="atbds_tab" data-tabArea="atbds_system-status-tab"><?php _e( 'Warning ', 'directorist' ); ?><?php echo $warning_count; ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#atbds_r-viewing" class="nav-link" data-action="atbds_tab" data-tabArea="atbds_system-status-tab"><?php _e( 'Remote Viewing', 'directorist' ); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#atbds_support" class="nav-link" data-action="atbds_tab" data-tabArea="atbds_system-status-tab"><?php _e( 'Support', 'directorist' ); ?></a>
                    </li>
                </ul>
            </aside>
        </div><!-- ends: .atbds_col-left -->
        <div class="atbds_col-right">
            <div class="atbds_content">
                <div class="tab-content" id="myTabContent" data-tabArea="atbds_system-status-tab">
                    <?php 
                        new ATBDP_System_Info();
                    ?>
                    <div class="tab-pane" id="atbds_warning">
                        <?php 
                        include_once ATBDP_INC_DIR . 'system-status/warning.php';
                        ?>
                    </div><!-- ends: .tab-pane -->
                    <div class="tab-pane" id="atbds_support">
                        <?php 
                        $this->send_mail->send_email_to(); 
                        ?>
                    </div><!-- ends: .tab-pane -->
                    <div class="tab-pane" id="atbds_r-viewing">
                        <?php
                        $custom_url = new ATBDP_Custom_Url();
                        $custom_url->custom_link();
                        ?>              
                    </div><!-- ends: .tab-pane -->
                </div>
            </div>
        </div><!-- ends: .atbds_col-right -->
    </div>
</section>
<section class="atbds_wrapper">
    <div class="row">
        <div class="col-12">
            <h2 class="atbds_title">System Status</h2>
        </div>
    </div>
    <div class="atbds_row">
        <div class="atbds_col-left">
            <aside class="atbds_sidebar pl-30">
                <ul class="nav" class="atbds_status-nav" id="atbds_status-tab" role="tablist">
                    <li class="nav-item">
                        <a href="#atbds_system-info" class="nav-link active" data-action="atbds_tab" data-tabArea="atbds_system-status-tab">System Information</a>
                    </li>
                    <li class="nav-item">
                        <a href="#atbds_warning" class="nav-link" data-action="atbds_tab" data-tabArea="atbds_system-status-tab">Warning</a>
                    </li>
                    <li class="nav-item">
                        <a href="#atbds_support" class="nav-link" data-action="atbds_tab" data-tabArea="atbds_system-status-tab">Support</a>
                    </li>
                    <li class="nav-item">
                        <a href="#atbds_r-viewing" class="nav-link" data-action="atbds_tab" data-tabArea="atbds_system-status-tab">Remote Viewing</a>
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
                        <div class="card atbds_card">
                            <div class="card-head">
                                <h4>Warning</h4>
                            </div>
                            <div class="card-body">
                                <div class="atbds_content__tab">
                                    <div class="atbds_warnings">
                                        <div class="atbds_row">
                                            <div class="atbds_col-4">
                                                <div class="atbds_warnings__single atbds_text-center">
                                                    <div class="atbds_warnings__icon">
                                                        <i class="la la-exclamation-triangle"></i>
                                                    </div>
                                                    <div class="atbds_warnigns__content">
                                                        <h4>Add Listing Page Not Selected</h4>
                                                        <p>Contains a collection of relevant data that will help you debug your website accurately and more efficiently.</p>
                                                        <a href="#" class="atbds_btnLink">Select Page <i class="la la-angle-right"></i></a>
                                                    </div>
                                                </div><!-- ends: .atbds_warnings__single -->
                                            </div>
                                            <div class="atbds_col-4">
                                                <div class="atbds_warnings__single atbds_text-center">
                                                    <div class="atbds_warnings__icon">
                                                        <i class="la la-exclamation-triangle"></i>
                                                    </div>
                                                    <div class="atbds_warnigns__content">
                                                        <h4>Add Listing Page Not Selected</h4>
                                                        <p>Contains a collection of relevant data that will help you debug your website accurately and more efficiently.</p>
                                                        <a href="#" class="atbds_btnLink">Select Page <i class="la la-angle-right"></i></a>
                                                    </div>
                                                </div><!-- ends: .atbds_warnings__single -->
                                            </div>
                                            <div class="atbds_col-4">
                                                <div class="atbds_warnings__single atbds_text-center">
                                                    <div class="atbds_warnings__icon">
                                                        <i class="la la-exclamation-triangle"></i>
                                                    </div>
                                                    <div class="atbds_warnigns__content">
                                                        <h4>Add Listing Page Not Selected</h4>
                                                        <p>Contains a collection of relevant data that will help you debug your website accurately and more efficiently.</p>
                                                        <a href="#" class="atbds_btnLink">Select Page <i class="la la-angle-right"></i></a>
                                                    </div>
                                                </div><!-- ends: .atbds_warnings__single -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- ends: .tab-pane -->
                    <div class="tab-pane" id="atbds_support">
                        <div class="card atbds_card">
                            <div class="card-head">
                                <h4>Support</h4>
                            </div>
                            <div class="card-body">
                                <div class="atbds_content__tab">
                                    <div class="atbds_supportForm">
                                        <h4>Send To</h4>
                                        <p>Contains a collection of relevant data that will help you debug your website accurately and more efficiently.</p>

                                        <form action="#">
                                            <div class="atbds_form-row">
                                                <label>Email Address</label>
                                                <input type="email" name="atbds_supportMail">
                                            </div>
                                            <div class="atbds_form-row">
                                                <label>Subject</label>
                                                <input type="email" name="atbds_supportMail">
                                            </div>
                                            <div class="atbds_form-row">
                                                <label>Additional Message</label>
                                                <textarea name="" id="" ></textarea>
                                            </div>
                                            <div class="atbds_form-row">
                                                <div class="atbds_customCheckbox">
                                                    <input type="checkbox" name="atbds_check_support" id="atbds_check_support">
                                                    <label for="atbds_check_support">Your system information will be attached automatically to this email.</label>
                                                </div>
                                            </div>
                                            <div class="atbds_form-row">
                                                <button class="atbds_btn atbds_btnPrimary">Send Mail</button>
                                            </div>
                                        </form>
                                    </div><!-- ends: .atbds_supportForm -->
                                </div>
                            </div>
                        </div>
                    </div><!-- ends: .tab-pane -->
                    <div class="tab-pane" id="atbds_r-viewing">
                        <div class="card atbds_card">
                            <div class="card-head">
                                <h4>Remote Viewing</h4>
                            </div>
                            <div class="card-body">
                                <div class="atbds_content__tab">
                                    <div class="atbds_remoteViewingForm">
                                        <p>Create a secret URL that support can use to remotely view your system information. The secret URL will expire after 72 hours and can be revoked at any time.</p>
                                        <form action="#">
                                            <div class="atbds_form-row">
                                                <input type="url" name="atbd_uniqueUrl">
                                                <button type="submit" class="atbds_btn atbds_btnPrimary atbds_btnBordered btn-test">Test</button>
                                            </div>
                                            <div class="atbds_form-row">
                                                <div class="atbds_buttonGroup">
                                                    <button class="atbds_btn atbds_btnDark">Create Url</button>
                                                    <button class="atbds_btn atbds_btnGray">Revoke Url</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- ends: .tab-pane -->
                </div>
            </div>
        </div><!-- ends: .atbds_col-right -->
    </div>
</section>
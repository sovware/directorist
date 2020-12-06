<div id="directorist" class="wrap atbd_wrapper">
    <div class="account-connect">
        <h4>Connect your Directorist account</h4>
        <p>Log in using your Directorist account email and password to manage your extensions and themes.</p>
        <div class="account-connect__form">
            <form action="">
                <div class="account-connect__form-group">
                    <input type="email" placeholder="Email address">
                    <span class="la la-envelope"></span>
                </div>
                <div class="account-connect__form-group">
                    <input type="password" placeholder="Password">
                    <span class="la la-lock"></span>
                </div>
                <div class="account-connect__form-btn">
                    <button type="submit" class="account-connect__btn">Connect <span class="la la-arrow-right"></span></button>
                </div>
            </form>
        </div>
    </div>
    <!-- my-themes-extensions -->
    <div id="my-themes-extensions" class="atbdp-tab-content active">
        <?php ATBDP()->load_template('theme-extensions/auth/license-auth-section', $args ); ?>
        <?php ATBDP()->load_template('theme-extensions/statistics/statistics', $args); ?>
        <?php ATBDP()->load_template('theme-extensions/my-themes-extensions/my-themes-extensions', $args); ?>
        <?php ATBDP()->load_template('theme-extensions/all-themes-extensions', $args); ?>
    </div>
</div>
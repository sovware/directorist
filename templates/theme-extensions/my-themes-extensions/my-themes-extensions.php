<div class="et-wrapper">
    <div class="et-wrapper-head">
        <h3>My Themes and Extensions <a href="" class="ext-action-btn"><i class="la la-refresh"></i> Refresh Purchase</a></h3>
        <div class="et-search">
            <input type="text" placeholder="Search extensions and themes">
            <span class="la la-search"></span>
        </div>
    </div><!-- ends: .et-wrapper-head -->
    <div class="et-contents">
        <ul class="et-contents__tab-nav">
            <li class="et-contents__tab-nav-item atbdp-tab__nav-item active">
                <a href="#" class="atbdp-tab__nav-link" data-parent="#atbdp-themes-extensions-contents" data-target="#atbdp-extensions-tab">
                    Extensions
                </a>
            </li>

            <li class="et-contents__tab-nav-item atbdp-tab__nav-item">
                <a href="#" class="atbdp-tab__nav-link" data-parent="#atbdp-themes-extensions-contents" data-target="#atbdp-themes-tab">
                    Themes
                </a>
            </li>
        </ul>

        <div id="atbdp-themes-extensions-contents" class="et-contents__tab-contents atbdp-tab__contents">
            <?php ATBDP()->load_template('theme-extensions/my-themes-extensions/extensions-tab', $args); ?>
            <?php ATBDP()->load_template('theme-extensions/my-themes-extensions/themes-tab', $args); ?>
        </div>
    </div><!-- ends: .et-contents -->
</div><!-- ends: .et-wrapper -->
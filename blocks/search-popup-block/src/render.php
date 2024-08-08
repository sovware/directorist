<div class="dspb-search">
    <?php if('icon_only' === $attributes['styleDisplay'] ): ?>
        <?php directorist_icon( 'la times' );?>
    <?php elseif('button_only' === $attributes['styleDisplay'] ): ?>
        <?php echo $content; ?>
    <?php elseif('button_and_icon' === $attributes['styleDisplay'] ): ?>
        <?php directorist_icon( 'la times' );?>
        <?php echo $content; ?>
    <?php endif;?>
</div>

<div class="search-modal">
    <?php echo do_shortcode( '[directorist_search_listing more_filters_button="no" show_title_subtitle="no" show_popular_category="no"]' ); ?>
</div>

<script>
"use strict";

function login() {
    const elements = {
        clickBtn: document.querySelector(".dspb-search .wp-block-button__link"),
        loginInBtn: document.querySelector(".directory_regi_btn button"),
        popup: document.getElementById("theme-login-modal"),
        closeBtn: document.querySelector("#theme-login-modal .theme-close"),
        signupBtn: document.querySelector(".directory_login_btn button"),
        signupPopup: document.getElementById("theme-register-modal"),
        signupCloseBtn: document.querySelector("#theme-register-modal .theme-close")
    };

    console.log('elements : ', elements );

    const showModal = (modal) => modal.style.display = 'block';
    const hideModal = (modal) => modal.style.display = 'none';
    const toggleModals = (hide, show) => {
        hideModal(hide);
        showModal(show);
    };

    console.log(' elements.popup : ',  elements.popup );
    // Adding event listener for clickBtn to show the login popup
    if (elements.clickBtn) {
        elements.clickBtn.addEventListener('click', () => showModal(elements.popup));
    }

    // Adding event listener for closeBtn to hide the login popup
    if (elements.closeBtn) {
        elements.closeBtn.addEventListener('click', () => hideModal(elements.popup));
    }

    // Adding event listener for popup to hide the login popup when clicking outside of it
    if (elements.popup) {
        elements.popup.addEventListener('click', (event) => {
            if (event.target === elements.popup) hideModal(elements.popup);
        });
    }

    // Adding event listener for signupBtn to toggle modals
    if (elements.signupBtn) {
        elements.signupBtn.addEventListener('click', (event) => {
            event.preventDefault();
            toggleModals(elements.popup, elements.signupPopup);
        });
    }

    // Adding event listener for signupCloseBtn to hide the signup popup
    if (elements.signupCloseBtn) {
        elements.signupCloseBtn.addEventListener('click', () => hideModal(elements.signupPopup));
    }

    // Adding event listener for signupPopup to hide the signup popup when clicking outside of it
    if (elements.signupPopup) {
        elements.signupPopup.addEventListener('click', (event) => {
            if (event.target === elements.signupPopup) hideModal(elements.signupPopup);
        });
    }

    // Adding event listener for loginInBtn to toggle modals
    if (elements.loginInBtn) {
        elements.loginInBtn.addEventListener('click', (event) => {
            event.preventDefault();
            toggleModals(elements.signupPopup, elements.popup);
        });
    }
}

document.addEventListener('DOMContentLoaded', login);
</script>
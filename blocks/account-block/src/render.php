<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 * @package block-developer-examples
 */

// wp_enqueue_style( 'directorist_account_block_styles' );
wp_enqueue_script( 'directorist-account' );

if ( ! is_user_logged_in() ) { ?>
    <?php include_once DIRECTORIST_ACCOUNT_BLOCK_TEMPLATE_PATH . '/account.php'; ?>
    <div class="dab-logout-mode"><?php echo $content; ?></div>

<?php } else { ?>

    <div class="dab-logged-mode">
        <?php
        if ( ! empty( $attributes['showUserAvatar'] ) ) {
            directorist_account_block_avatar_image();
        }
        if ( ! empty( $attributes['showDashboardMenu'] ) ) {
            include_once DIRECTORIST_ACCOUNT_BLOCK_TEMPLATE_PATH . '/navigation.php';
        }
        ?>
    </div>

<?php } ?>

<script>
"use strict";

function authorDropdownActive() {
    const authorTrigger = document.querySelector('.dab-logged-mode .avatar');
    let shade = document.querySelector('.dab-logged-mode__overlay');
    const authorDropdown = document.querySelector('.dab-logged-mode__navigation');

    // Function to toggle the dropdown and shade visibility
    function toggleAuthorDropdown() {
        authorDropdown.classList.toggle('show');
        shade.classList.toggle('show');
    }

    // Function to remove the dropdown and shade visibility
    function removeDropdown() {
        authorDropdown.classList.remove('show');
        shade.classList.remove('show');
    }

    // Check if the shade element exists; if not, create it
    if (!shade) {
        shade = document.createElement('div');
        shade.className = 'dab-logged-mode__overlay';
        document.body.appendChild(shade);
    }

    // Add event listeners if elements exist
    if (authorTrigger && shade) {
        authorTrigger.addEventListener('click', toggleAuthorDropdown);
        shade.addEventListener('click', removeDropdown);
    }
}

document.addEventListener('DOMContentLoaded', authorDropdownActive);

function login() {
    const elements = {
        clickBtn: document.querySelector(".dab-logout-mode .wp-block-button__link"),
        loginInBtn: document.querySelector(".directory_regi_btn button"),
        popup: document.getElementById("directorist-account-block-login-modal"),
        closeBtn: document.querySelector("#directorist-account-block-login-modal .directorist-account-block-close"),
        signupBtn: document.querySelector(".directory_login_btn button"),
        signupPopup: document.getElementById("directorist-account-block-register-modal"),
        signupCloseBtn: document.querySelector("#directorist-account-block-register-modal .directorist-account-block-close")
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
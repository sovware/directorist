"use strict";

function authorDropdownActive() {
    const authorTriggers = document.querySelectorAll('.directorist-account-block-logged-mode .avatar');

    authorTriggers.forEach((authorTrigger) => {
        const parentBlock = authorTrigger.closest('.directorist-account-block-logged-mode');
        let shade = parentBlock.querySelector('.directorist-account-block-logged-mode__overlay');
        const authorDropdown = parentBlock.querySelector('.directorist-account-block-logged-mode__navigation');

        // Function to toggle the dropdown and shade visibility
        function toggleAuthorDropdown() {
            if (authorDropdown && shade) {
                authorDropdown.classList.toggle('show');
                shade.classList.toggle('show');
            }
        }

        // Function to remove the dropdown and shade visibility
        function removeDropdown() {
            if (authorDropdown && shade) {
                authorDropdown.classList.remove('show');
                shade.classList.remove('show');
            }
        }

        // Check if the shade element exists; if not, create it
        if (!shade) {
            shade = document.createElement('div');
            shade.className = 'directorist-account-block-logged-mode__overlay';
            parentBlock.appendChild(shade);
        }

        // Add event listeners if elements exist
        if (authorTrigger && shade) {
            authorTrigger.addEventListener('click', toggleAuthorDropdown);
            shade.addEventListener('click', removeDropdown);
        }
    });
}

document.addEventListener('DOMContentLoaded', authorDropdownActive);

document.addEventListener('DOMContentLoaded', authorDropdownActive);

function login() {
    const elements = {
        clickBtns: document.querySelectorAll(".directorist-account-block-logout-mode .wp-block-button__link"),

        loginInBtn: document.querySelector(".directory_regi_btn button"),
        popup: document.getElementById("directorist-account-block-login-modal"),
        closeBtn: document.querySelector("#directorist-account-block-login-modal .directorist-account-block-close"),
        signupBtn: document.querySelector(".directory_login_btn button"),
        signupPopup: document.getElementById("directorist-account-block-register-modal"),
        signupCloseBtn: document.querySelector("#directorist-account-block-register-modal .directorist-account-block-close")
    };

    const showModal = (modal) => modal.style.display = 'block';
    const hideModal = (modal) => modal.style.display = 'none';
    const toggleModals = (hide, show) => {
        hideModal(hide);
        showModal(show);
    };

    elements.clickBtns.forEach((clickBtn, index) => { 
        clickBtn.addEventListener('click', () => showModal(elements.popup));
    });

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
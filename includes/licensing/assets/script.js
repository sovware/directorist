"use strict";

// New feature: Updates the modal install button's data-item-id
function updateModalInstallButton(modal, dataItemId) {
    const installButton = modal.querySelector('.directorist-insert-modal__install-templatiq');
    if (installButton && dataItemId) {
        installButton.setAttribute('data-item-id', dataItemId);
    }
}

//Toggles the visibility of specified elements when a button is clicked.
function toggleMembershipVisibility(closetClass, buttonSelector, modalSelector, closeButtonSelector) {
    const toggleElements = (parent, dataItemId) => {
        const modal = parent.querySelector(modalSelector);
        if (modal) {
            // Call the separate function to update the modal button
            updateModalInstallButton(modal, dataItemId);

            const isHidden = modal.classList.contains('directorist-d-none');
            requestAnimationFrame(() => {
                modal.classList.toggle('directorist-d-block', isHidden);
                modal.classList.toggle('directorist-d-none', !isHidden);
            });
        }
    };

    // Toggle modal on button click
    document.querySelectorAll(buttonSelector).forEach(button => {
        button.addEventListener('click', event => {
            event.stopPropagation();
            const parent = button.closest(closetClass);
            const dataItemId = button.getAttribute('data-item-id');
            if (parent) toggleElements(parent, dataItemId);
        });
    });

    // Close modal when clicking outside
    document.addEventListener('click', event => {
        document.querySelectorAll(modalSelector).forEach(modal => {
            if (!modal.contains(event.target)) {
                requestAnimationFrame(() => {
                    modal.classList.remove('directorist-d-block');
                    modal.classList.add('directorist-d-none');
                });
            }
        });
    });

    // Close modal on close button click
    document.querySelectorAll(closeButtonSelector).forEach(closeButton => {
        closeButton.addEventListener('click', event => {
            event.stopPropagation();
            const parent = closeButton.closest(closetClass);
            if (parent) {
                requestAnimationFrame(() => {
                    parent.querySelector(modalSelector).classList.remove('directorist-d-block');
                    parent.querySelector(modalSelector).classList.add('directorist-d-none');
                });
            }
        });
    });
};


// Function to initialize tab switching functionality
function initializeDirectoristTabs(containerSelector, tabSelector, contentSelector) {
    document.querySelectorAll(containerSelector).forEach(container => {
        const tabs = container.querySelectorAll(tabSelector);
        const contents = container.querySelectorAll(contentSelector);

        if (tabs.length > 0 && contents.length > 0) {
            container.addEventListener("click", function (event) {
                const tab = event.target.closest(tabSelector);
                if (!tab) return; // Ignore clicks outside tabs

                const targetId = tab.getAttribute("data-target"); // Get target ID
                const targetContent = container.querySelector(targetId);

                if (!targetContent) return; // If target not found, do nothing

                // Remove 'active' class from all tabs and contents in this container only
                tabs.forEach(t => t.classList.remove("active"));
                contents.forEach(c => {
                    c.style.display = "none"; // Hide all content sections
                    c.classList.remove("active");
                });

                // Activate the clicked tab and show the associated content
                tab.classList.add("active");
                targetContent.style.display = "block";
                requestAnimationFrame(() => targetContent.classList.add("active")); // Ensures smooth transition
            });
        }
    });
};

// Function to add an 'active' class to the parent when a pricing tab is clicked
function handlePricingTabClick(containerSelector, tabsSelector, parentSelector) {
    document.querySelectorAll(containerSelector).forEach(container => {
        const tabs = container.querySelectorAll(tabsSelector);
        const parent = container.closest(parentSelector);
        if (!parent) return; // Ensure parent exists

        // Function to update active class
        function updateActiveClass(tab) {
            // Remove all existing "tab-X-active" classes from parent
            parent.classList.forEach(className => {
                if (className.startsWith("tab-") && className.endsWith("-active")) {
                    parent.classList.remove(className);
                }
            });

            // Add a new active class based on the tab index
            const index = [...tabs].indexOf(tab);
            if (index !== -1) {
                parent.classList.add(`tab-${index + 1}-active`);
            }
        }

        // Set initial active class
        const initialActiveTab = container.querySelector(`${tabsSelector}.active`);
        if (initialActiveTab) {
            updateActiveClass(initialActiveTab);
        }

        // Handle click events to update active class
        container.addEventListener("click", function (event) {
            const tab = event.target.closest(tabsSelector);
            if (!tab) return;
            updateActiveClass(tab);
        });
    });
};

//ProgressBar
function progressbar(target) {
    document.querySelectorAll(target).forEach(progress => {
        progress.style.width = progress.getAttribute('data-done') + '%';
        progress.style.opacity = 1;
    });
};

// Select the form based on the provided selector
function updateSubmitButtonState(button, validClass, loadingText, errorClass, defaultText, isLoading = false) {
    if (!button || !(button instanceof HTMLElement)) return;

    // Remove both states first
    button.classList.remove(validClass, errorClass);

    // Update text and class based on state
    if (isLoading) {
        button.textContent = loadingText;
        button.classList.add(validClass);
        button.disabled = true;
    } else {
        button.textContent = defaultText;
        button.classList.add(errorClass);
        button.disabled = false;
    }
}

function handlePostRequest(formSelector, endpoint, successCallback, errorCallback, buttonStateConfig) {
    endpoint = directorist_licensing.root + endpoint || endpoint;
    document.querySelectorAll(formSelector).forEach(form => {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            const submitButton = form.querySelector("button[type='submit']");
            updateSubmitButtonState(
                submitButton,
                buttonStateConfig.validClass,
                buttonStateConfig.loadingText,
                buttonStateConfig.errorClass,
                buttonStateConfig.defaultText,
                true // always true on start
            );
            if (form.checkValidity()) {
                const formData = new FormData(form);
                const formDataObject = {};
                formData.forEach((value, key) => {
                    formDataObject[key] = value;
                });

                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': directorist_licensing.nonce
                    },
                    body: JSON.stringify(formDataObject)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (successCallback) {
                        successCallback(data, submitButton, buttonStateConfig);
                    }
                })
                .catch(error => {
                    if (errorCallback) {
                        errorCallback(error, submitButton, buttonStateConfig);
                    }
                });
            } else {
                form.reportValidity();
            }
        });
    });
}

function handleSearch({ inputSelector, searchGroups }) {
    const searchInput = document.querySelector(inputSelector);
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();

        searchGroups.forEach(group => {
            const container = document.querySelector(group.containerSelector);
            if (!container) return;

            const items = container.querySelectorAll(group.itemSelector);

            items.forEach(item => {
                const title = item.querySelector(group.titleSelector)?.textContent.toLowerCase() || '';
                const description = item.querySelector(group.descriptionSelector)?.textContent.toLowerCase() || '';

                item.style.display = (title.includes(query) || description.includes(query)) ? '' : 'none';
            });
        });
    });
}


document.addEventListener("DOMContentLoaded", function () {

    handlePostRequest(
        ".directorist-login-with-access-key",
        "directorist/v1/admin/login-with-access-key",
        function (data, button, config) {
            const modal = document.querySelector('.directorist-licensing-modal');
            if (data.success === true) {
                modal.style.display = 'block';
                modal.querySelector('.directorist-licensing-alert-success').style.display = 'block';
                modal.querySelector('.directorist-licensing-alert-error').style.display = 'none';
                setTimeout(() => {
                    location.reload();
                }, 3);
            } else {
                modal.style.display = 'block';
                modal.querySelector('.directorist-licensing-alert-success').style.display = 'none';
                modal.querySelector('.directorist-licensing-alert-error').style.display = 'block';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 3000);
                console.log(data);
                updateSubmitButtonState(
                    button,
                    config.validClass,
                    config.loadingText,
                    config.errorClass,
                    config.defaultText,
                    false
                );
            }
        },
        function (error) {
            const modal = document.querySelector('.directorist-licensing-modal');
            modal.style.display = 'block';
            modal.querySelector('.directorist-licensing-alert-success').style.display = 'none';
            modal.querySelector('.directorist-licensing-alert-error').style.display = 'block';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 3000);
            console.error("Error:", error);
        },
        {
            validClass: "valid-submit",
            loadingText: "Connecting...",
            errorClass: "failed-submit",
            defaultText: "Connect Now"
        }
    );

    handlePostRequest(
        ".directorist-login-with-account",
        "directorist/v1/admin/login-with-account",
        function (data, button, config) {
            const modal = document.querySelector('.directorist-licensing-modal');
            if (data.success === true) {
                modal.style.display = 'block';
                modal.querySelector('.directorist-licensing-alert-success').style.display = 'block';
                modal.querySelector('.directorist-licensing-alert-error').style.display = 'none';
                setTimeout(() => {
                    location.reload();
                }, 3);
            } else {
                modal.style.display = 'block';
                modal.querySelector('.directorist-licensing-alert-success').style.display = 'none';
                modal.querySelector('.directorist-licensing-alert-error').style.display = 'block';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 3000);
                updateSubmitButtonState(
                    button,
                    config.validClass,
                    config.loadingText,
                    config.errorClass,
                    config.defaultText,
                    false
                );
            }
        },
        function (error) {
            const modal = document.querySelector('.directorist-licensing-modal');
            modal.style.display = 'block';
            modal.querySelector('.directorist-licensing-alert-success').style.display = 'none';
            modal.querySelector('.directorist-licensing-alert-error').style.display = 'block';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 3000);
            console.error("Error:", error);
        },
        {
            validClass: "valid-submit",
            loadingText: "Login...",
            errorClass: "failed-submit",
            defaultText: "Log In with Directorist Account"
        }
    );

    const activateBtn = document.getElementById("directorist-activate-license");
    if(activateBtn){
        document.getElementById("directorist-activate-license").addEventListener("click", function () {
            const licenseKey = document.getElementById("directorist-license-key").value;
            const licenseEmail = document.getElementById("directorist-license-email").value;
            const license = {
                license_key: licenseKey,
                license_email: licenseEmail
            };
        })
    }
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".directorist-extension-btn-install").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            
            const extensionSlug = this.getAttribute("data-item-slug");
            if (!extensionSlug) return;
            
            this.textContent = "Installing...";
            this.disabled = true;
            
            fetch(directorist_licensing.root + "directorist/v1/admin/install-extension", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": directorist_licensing.nonce
                },
                body: JSON.stringify({ slug: extensionSlug })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = "Installed";
                    this.classList.remove("directorist-extension-btn-install");
                    this.classList.add("directorist-extension-btn-installed");
                } else {
                    this.textContent = "Install";
                    alert(data.message || "Installation failed.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                this.textContent = "Install";
                alert("An error occurred while installing the extension.");
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
    document.querySelectorAll(".directorist-extension-btn-install").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            
            const extensionSlug = this.getAttribute("data-item-slug");
            const theme_id = this.getAttribute("data-item-id");
            if (!extensionSlug) return;
            
            this.textContent = "Installing...";
            this.disabled = true;
            
            fetch(directorist_licensing.root + "directorist/v1/admin/install-extension", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": directorist_licensing.nonce
                },
                body: JSON.stringify({ slug: extensionSlug, theme_id: theme_id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if( 'templatiq' === extensionSlug ){
                        this.textContent = "Installed & Activated";
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        this.textContent = "Installed";
                        this.classList.remove("directorist-extension-btn-install");
                        this.classList.add("directorist-extension-btn-installed");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                } else {
                    this.textContent = "Install";
                    alert(data.message || "Installation failed.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                this.textContent = "Install";
                alert("An error occurred while installing the extension.");
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });

    document.querySelectorAll(".directorist-extension-btn-activate").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
    
            const extensionSlug = this.getAttribute("data-item-slug");
            if (!extensionSlug) return;
    
            this.textContent = "Activating...";
            this.disabled = true;
    
            fetch(directorist_licensing.root + "directorist/v1/admin/activate-extension", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": directorist_licensing.nonce
                },
                body: JSON.stringify({ slug: extensionSlug })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = "Activated";
                    this.classList.remove("directorist-extension-btn-activate");
                    this.classList.add("directorist-extension-btn-activated");
                    location.reload();
                } else {
                    this.textContent = "Activate";
                    alert(data.message || "Activation failed.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                this.textContent = "Activate";
                alert("An error occurred while activating the extension.");
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });

    document.querySelectorAll(".directorist-extension-btn-deactivate").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
    
            const extensionSlug = this.getAttribute("data-item-slug");
            if (!extensionSlug) return;
    
            this.textContent = "Deactivating...";
            this.disabled = true;
    
            fetch(directorist_licensing.root + "directorist/v1/admin/deactivate-extension", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": directorist_licensing.nonce
                },
                body: JSON.stringify({ slug: extensionSlug })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = "Deactivated";
                    this.classList.remove("directorist-extension-btn-deactivate");
                    this.classList.add("directorist-extension-btn-deactivated");
                    location.reload();
                } else {
                    this.textContent = "Deactivate";
                    alert(data.message || "Deactivation failed.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                this.textContent = "Deactivate";
                alert("An error occurred while deactivating the extension.");
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
    document.querySelectorAll(".directorist-extension-btn-update").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            
            const extensionSlug = this.getAttribute("data-item-slug");
            if (!extensionSlug) return;
            
            this.textContent = "Updating...";
            this.disabled = true;
            
            fetch(directorist_licensing.root + "directorist/v1/admin/update-extension", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": directorist_licensing.nonce
                },
                body: JSON.stringify({ slug: extensionSlug })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = "Updated";
                    this.classList.remove("directorist-extension-btn-update");
                    this.classList.add("directorist-extension-btn-updated");
                } else {
                    this.textContent = "Update";
                    alert(data.message || "Updating failed.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                this.textContent = "Update";
                alert("An error occurred while updating the extension.");
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
});


// Wait until the DOM is fully loaded before initializing the tabs
document.addEventListener("DOMContentLoaded", function () {
    // Call the function with your specific selectors
    toggleMembershipVisibility('.directorist-membership-section', '.directorist-membership-card-signin', '.directorist-login-form');
    toggleMembershipVisibility('.directorist-membership-info-author', '.directorist-membership-info-author-img', '.directorist-membership-info-author-dropdown');
    toggleMembershipVisibility('main', '.directorist-membership-status-update-all', '.directorist-membership-modal','.directorist-membership-modal .directorist-custom-modal-close');
    toggleMembershipVisibility('main', '.directorist-install-templatiq', '.directorist-insert-modal','.directorist-insert-modal .directorist-custom-modal-close, .directorist-insert-modal__cancel');
    initializeDirectoristTabs(".directorist-tabs", ".directorist-nav-tab", ".directorist-tabs-item");
    handlePricingTabClick(".directorist-nav-tab-wrapper", "button", ".directorist-nav-tab-wrapper");
    progressbar(".directorist-progress-inner");
    handleSearch({
        inputSelector: '.directorist-product-search-input',
        searchGroups: [
            {
                containerSelector: '#directorist-extensions .directorist-row',
                itemSelector: '#directorist-extensions .directorist-col-xxl-3',
                titleSelector: '.directorist-extension-title',
                descriptionSelector: '.directorist-extension-description'
            },
            {
                containerSelector: '#directorist-templates .directorist-row',
                itemSelector: '#directorist-templates .directorist-col-xxl-3',
                titleSelector: '.directorist-template-title',
                descriptionSelector: '.directorist-template-description'
            }
        ]
    });
});
"use strict";

//Toggles the visibility of specified elements when a button is clicked.
function toggleMembershipVisibility(closetClass, buttonSelector, hideSelector, showSelector) {
    document.querySelectorAll(buttonSelector).forEach(button => {
        button.addEventListener('click', () => {
            const parent = button.closest(closetClass);
            if (parent) {
                [hideSelector, showSelector].forEach(selector => {
                    const element = parent.querySelector(selector);
                    if (element) {
                        const isHidden = element.classList.contains('directorist-d-none');
                        requestAnimationFrame(() => {
                            // element.style.display = isHidden ? 'block' : 'none';
                            element.classList.toggle('directorist-d-block', isHidden);
                            element.classList.toggle('directorist-d-none', !isHidden);
                        });
                    }
                });
            }
        });
    });
}

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
}

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
}


// Wait until the DOM is fully loaded before initializing the tabs
document.addEventListener("DOMContentLoaded", function () {
    // Call the function with your specific selectors
    toggleMembershipVisibility('.directorist-membership-section', '.directorist-membership-card-signin', '.directorist-login-form');
    initializeDirectoristTabs(".directorist-tabs", ".directorist-nav-tab", ".directorist-tabs-item");
    handlePricingTabClick(".directorist-nav-tab-wrapper", "button", ".directorist-nav-tab-wrapper");
});
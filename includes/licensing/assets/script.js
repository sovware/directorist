"use strict";

// Function to initialize tab switching functionality
function initializeDirectoristTabs(tabSelector, contentSelector) {
    const tabs = document.querySelectorAll(tabSelector);
    const contents = document.querySelectorAll(contentSelector);

    if (tabs.length > 0 && contents.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target"); // Get target ID from button
                const targetContent = document.querySelector(targetId);

                if (!targetContent) return; // If target not found, do nothing

                // Remove 'active' class from all tabs and contents
                tabs.forEach(t => t.classList.remove("active"));
                contents.forEach(c => {
                    c.style.display = "none"; // Hide all content sections
                    c.classList.remove("active");
                });

                // Add 'active' class to the clicked tab
                this.classList.add("active");

                // Smooth transition effect
                setTimeout(() => {
                    targetContent.style.display = "block";
                    setTimeout(() => targetContent.classList.add("active"), 10);
                }, 50);
            });
        });
    }
}

// Function to add an 'active' class to the parent when the pricing tab is clicked
function handlePricingTabClick(tabsSelector, parentSelector) {
	const tabs = document.querySelectorAll(tabsSelector);
	const parent = document.querySelector(parentSelector);
	if(tabsSelector && parentSelector){
		tabs.forEach((tab, index) => {
			tab.addEventListener("click", function () {
				parent.classList.forEach(className => {
					if (className.startsWith("tab-") && className.endsWith("-active")) {
						parent.classList.remove(className);
					}
				});
				const newClass = `tab-${index + 1}-active`;
				parent.classList.add(newClass);
			});
		});
	}
}

// Wait until the DOM content is fully loaded before initializing the tabs
document.addEventListener("DOMContentLoaded", function () {
    // Initialize the Directorist tabs (with tab and content selectors)
    initializeDirectoristTabs(".directorist-nav-tab", ".directorist-tab-content");
    // Handle the pricing tab click (add active class to parent element)
    handlePricingTabClick(".directorist-nav-tab-wrapper button", ".directorist-nav-tab-wrapper");
});
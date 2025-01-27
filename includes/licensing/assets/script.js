"use strict";

// Function to initialize tab switching functionality
function initializeDirectoristTabs(tab, content) {
    const tabs = document.querySelectorAll(tab);
    const contents = document.querySelectorAll(content);

    if (tabs.length > 0 && contents.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener("click", function (e) {
                e.preventDefault();

                const targetId = this.getAttribute("href");
                const targetContent = document.querySelector(targetId);

                tabs.forEach(t => t.classList.remove("active"));
                contents.forEach(c => {
                    c.style.display = "none"; // Hide all
                    c.classList.remove("active");
                });

                this.classList.add("active");
                // Smooth transition: delay display to allow fade effect
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
    handlePricingTabClick(".directorist-nav-tab-wrapper a", ".directorist-nav-tab-wrapper");
});
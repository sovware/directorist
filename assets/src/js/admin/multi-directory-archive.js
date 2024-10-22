// Scrips
import './components/delete-directory-modal';
import './components/directory-migration-modal';
import './components/import-directory-modal';

window.addEventListener('load', () => {
    var $ = jQuery;
    const axios = require('axios').default;

    // Migration Link
    $( '.directorist_directory_template_library' ).on( 'click', function( e ) {
        e.preventDefault();
        const self = this;

        $( '.cptm-create-directory-modal__action' ).after( "<span class='directorist_template_notice'>Installing Templatiq, Please wait..</span>" );

        let form_data = new FormData();
        form_data.append( 'action', 'directorist_directory_type_library' );
        form_data.append('directorist_nonce', directorist_admin.directorist_nonce);

        // Response Success Callback
        const responseSuccessCallback = function ( response ) {

            if ( response?.data?.success ) {
                let msg = ( response?.data?.message ) ?? 'Imported successfully!';

                $( '.directorist_template_notice' ).text( msg );

                location.reload();
                return;
            }

            responseFaildCallback( response );
        };

        // Response Error Callback
        const responseFaildCallback = function ( response ) {
            // console.log( { response } );

            let msg = ( response?.data?.message ) ?? 'Something went wrong please try again';
            let alert_content = `
            <div class="cptm-section-alert-content">
                <div class="cptm-section-alert-icon cptm-alert-error">
                    <span class="fa fa-times"></span>
                </div>

                <div class="cptm-section-alert-message">${msg}</div>
            </div>
            `;

            $( '.cptm-directory-migration-form' ).find( '.cptm-comfirmation-text' ).html( alert_content );
            $( self ).remove();
        };

        // Send Request
        axios.post( directorist_admin.ajax_url, form_data ).then( response => {
            responseSuccessCallback( response );
        }).catch( response => {
            responseFaildCallback( response );
        });
    });


    // show the form
    $( '.directorist-ai-directory-creation' ).on( 'click', function( e ) {
        e.preventDefault();
        const self = this;

        console.log("rony");

        let form_data = new FormData();
        form_data.append( 'action', 'directorist_ai_directory_form' );

        // Response Success Callback
        const responseAiFormSuccess = function ( response ) {

            if ( response?.data?.success ) {
                $( '#wpbody' ).empty().html( response?.data?.html );

                initializeKeyword();
                initializeProgressBar();
                initializeDropdownField();

                return;
            }

            alert('Something went wrong! Please try again');
        };

        // Send Request
        axios.post( directorist_admin.ajax_url, form_data ).then( response => {
            responseAiFormSuccess( response );
        }).catch( response => {
            alert('Something went wrong! Please try again');
        });
    });

});

// Function to initialize Keyword Selected
function initializeKeyword() {
    (function () {
        const tagList = []; // Select default keyword
        const maxFreeTags = 5; // Max item limit for all users

        const tagListElem = document.getElementById("directorist-box__tagList");
        const newTagElem = document.getElementById("directorist-box__newTag");
        const recommendedTagsElem = document.getElementById("directorist-recommendedTags");
        const recommendedTags = Array.from(recommendedTagsElem.getElementsByTagName("li"));
        const tagLimitMsgElem = document.getElementById("directorist-tagLimitMsg");
        const tagCountElem = document.getElementById("directorist-tagCount");

        const initTagManagement = () => {
            renderTagList();
            updateRecommendedTagsState();
        };

        const renderTagList = () => {
            tagListElem.innerHTML = "";
            tagList.forEach((tag) => {
                const li = document.createElement("li");
                li.innerHTML = `${tag} <span class="directorist-rmTag" style="cursor:pointer;">&times;</span>`;
                tagListElem.appendChild(li);
            });

            const inputLi = document.createElement("li");
            inputLi.appendChild(newTagElem);
            tagListElem.appendChild(inputLi);

            updateRecommendedTagsState();
            updateTagCount();
        };

        const canAddMoreTags = () => tagList.length < maxFreeTags;

        const updateTagCount = () => {
            const tagCount = tagList.length;
            tagCountElem.innerHTML = `${tagCount}/${maxFreeTags}`;
            // Always display the tag limit message
            tagLimitMsgElem.style.display = "flex";
            // Add or remove 'recommend-disable' class based on the tag limit
            if (canAddMoreTags()) {
                recommendedTagsElem.classList.remove("recommend-disable");
            } else {
                recommendedTagsElem.classList.add("recommend-disable");
            }
        };

        newTagElem.addEventListener("keyup", (e) => {
            if (e.key === "Enter") {
                const newTag = newTagElem.value.trim();
                if (newTag && !tagList.includes(newTag) && canAddMoreTags()) {
                    tagList.push(newTag);
                    newTagElem.value = "";
                    renderTagList();
                    newTagElem.focus();
                }
            }
        });

        tagListElem.addEventListener("click", (e) => {
            if (e.target.classList.contains("directorist-rmTag")) {
                const index = Array.from(tagListElem.children).findIndex((child) =>
                    child.contains(e.target)
                );
                if (index !== -1) {
                    tagList.splice(index, 1);
                    renderTagList();
                }
            }
        });

        recommendedTagsElem.addEventListener("click", (e) => {
            if (e.target.tagName === "LI" && !e.target.classList.contains("disabled")) {
                if (canAddMoreTags()) {
                    const recommendedTag = e.target.textContent.trim();
                    if (!tagList.includes(recommendedTag)) {
                        tagList.push(recommendedTag);
                        renderTagList();
                    }
                }
            }
        });

        const updateRecommendedTagsState = () => {
            recommendedTags.forEach((recommendedTagElem) => {
                const recommendedTag = recommendedTagElem.textContent.trim();
                recommendedTagElem.classList.toggle(
                    "disabled",
                    tagList.includes(recommendedTag)
                );
            });
        };

        initTagManagement();
    })();
}




// Function to initialize Progress bar
function initializeProgressBar() {
    const generateBtnWrapper = document.querySelector(".directory-generate-btn__wrapper");

    if (generateBtnWrapper) {
        const finalWidth = generateBtnWrapper.getAttribute("data-width");
        const btnPercentage = document.querySelector(".directory-generate-btn__percentage");
        const progressBar = document.querySelector(".directory-generate-btn--bg");

        let currentWidth = 0;

        const updateProgress = () => {
            if (currentWidth <= finalWidth) {
                btnPercentage.textContent = `${currentWidth}%`;
                progressBar.style.width = `${currentWidth}%`;

                if (typeof updateProgressList === 'function') {
                    updateProgressList(currentWidth);
                }

                currentWidth++;
            } else {
                clearInterval(progressInterval);
            }
        };

        const progressInterval = setInterval(updateProgress, 30);
    }

    const steps = document.querySelectorAll(".directory-generate-progress-list li");

    const updateProgressList = (progress) => {
        if (steps.length > 0) {
            steps.forEach((step, index) => {
                const stepNumber = index + 1;
                const stepThreshold = stepNumber * (100 / steps.length);

                if (progress >= stepThreshold) {
                    step.setAttribute("data-type", "completed");
                    step.querySelector(".completed-icon").style.display = "block";
                    step.querySelector(".progress-icon").style.display = "none";
                    step.querySelector(".default-icon").style.display = "none";
                } else if (progress < stepThreshold && progress >= stepThreshold - (100 / steps.length)) {
                    step.setAttribute("data-type", "progress");
                    step.querySelector(".completed-icon").style.display = "none";
                    step.querySelector(".progress-icon").style.display = "block";
                    step.querySelector(".default-icon").style.display = "none";
                } else {
                    step.setAttribute("data-type", "default");
                    step.querySelector(".completed-icon").style.display = "none";
                    step.querySelector(".progress-icon").style.display = "none";
                    step.querySelector(".default-icon").style.display = "block";
                }
            });
        }
    };
}
//Function to initialize Dropdown
function initializeDropdownField(){
    const dropdowns = document.querySelectorAll(".directorist-ai-generate-dropdown");
    const accordion = true;

    dropdowns.forEach((dropdown) => {
        const header = dropdown.querySelector(".directorist-ai-generate-dropdown__header");
        const content = dropdown.querySelector(".directorist-ai-generate-dropdown__content");
        const icon = dropdown.querySelector(".directorist-ai-generate-dropdown__header-icon");
        const pinIcon = dropdown.querySelector(".directorist-ai-generate-dropdown__pin-icon");
        const dropdownItem = dropdown.closest('.directorist-ai-generate-box__item');

        pinIcon.addEventListener("click", (event) => {
            event.stopPropagation();
            if (dropdownItem.classList.contains("pinned")) {
                dropdownItem.classList.remove("pinned");
                dropdownItem.classList.add("unpinned");
            } else {
                dropdownItem.classList.remove("unpinned");
                dropdownItem.classList.add("pinned");
            }
        });

        header.addEventListener("click", (event) => {
            if (event.target === pinIcon || pinIcon.contains(event.target)) {
                return;
            }

            const isExpanded = content.classList.toggle("directorist-ai-generate-dropdown__content--expanded");
            dropdown.setAttribute("aria-expanded", isExpanded);
            content.setAttribute("aria-expanded", isExpanded);
            icon.classList.toggle("rotate", isExpanded);

            if (accordion) {
                dropdowns.forEach((otherDropdown) => {
                    if (otherDropdown !== dropdown) {
                        const otherContent = otherDropdown.querySelector(".directorist-ai-generate-dropdown__content");
                        const otherIcon = otherDropdown.querySelector(".directorist-ai-generate-dropdown__header-icon");

                        otherContent.classList.remove("directorist-ai-generate-dropdown__content--expanded");
                        otherDropdown.setAttribute("aria-expanded", false);
                        otherContent.setAttribute("aria-expanded", false);
                        otherIcon.classList.remove("rotate");
                    }
                });
            }
        });
    });
}





var $ = jQuery;
const axios = require('axios').default;
// handle firm step
$('body').on( 'click', '.directorist_generate_ai_directory', function( e ) {
    e.preventDefault();
    const self = this;

    var step = $(self).data('step');
    let keywords = $('input[name="keywords[]"]:checked').map(function() {
        return this.value;
    }).get();

    let form_data = new FormData();
    form_data.append( 'action', 'directorist_ai_directory_creation' );
    form_data.append( 'prompt', $('.directorist-ai-prompt').val() );
    form_data.append( 'keywords', keywords );
    form_data.append( 'step', step );

    // Response Success Callback
    const responseAiFormSuccess = function ( response ) {

        if ( response?.data?.success ) {

            if( step == 1 ) {
                $( '#directorist-recommendedTags' ).empty().html( response?.data?.html );
                $(self).data('step', step + 1 );
            }

            if( step == 2 ) {

            }

            console.log( response );
            return;
        }

        alert('Something went wrong! Please try again');
    };

    // Send Request
    axios.post( directorist_admin.ajax_url, form_data ).then( response => {
        responseAiFormSuccess( response );
    }).catch( response => {
        alert('Something went wrong! Please try again');
    });
});
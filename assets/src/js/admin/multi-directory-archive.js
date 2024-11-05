// Scrips
import './components/delete-directory-modal';
import './components/directory-migration-modal';
import './components/import-directory-modal';

var $ = jQuery;
const axios = require('axios').default;

window.addEventListener('load', () => {
    // Migration Link
    $( '.directorist_directory_template_library' ).on( 'click', function( e ) {
        e.preventDefault();
        const self = this;
        // Add 'disabled' class to all siblings with the specific class and also to self
        $( self ).siblings( '.cptm-create-directory-modal__action__single' ).addBack().addClass( 'disabled' );


        $( '.cptm-create-directory-modal__action' ).after( "<span class='directorist_template_notice'>Installing Templatiq, Please wait..</span>" );

        let form_data = new FormData();
        form_data.append( 'action', 'directorist_directory_type_library' );
        form_data.append('directorist_nonce', directorist_admin.directorist_nonce);

        // Response Success Callback
        const responseSuccessCallback = function ( response ) {

            if ( response?.data?.success ) {
                let msg = ( response?.data?.message ) ?? 'Imported successfully!';
                $( '.directorist_template_notice' )
                .addClass( 'cptm-section-alert-success' )
                .text( msg );

                location.reload();
                return;
            }

            responseFieldCallback( response );
        };

        // Response Error Callback
        const responseFieldCallback = function ( response ) {
            // Remove 'disabled' class from all siblings and self in case of failure
            $( self ).siblings( '.cptm-create-directory-modal__action__single' ).addBack().removeClass( 'disabled' );

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
            responseFieldCallback( response );
        });
    });

    // Show the form when the '.directorist-ai-directory-creation' element is clicked
    $('.directorist-ai-directory-creation').on('click', function(e) {
        e.preventDefault();

        // Prepare form data for the request
        const form_data = new FormData();

        form_data.append('action', 'directorist_ai_directory_form');

        // Send the request using Axios
        axios.post(directorist_admin.ajax_url, form_data)
            .then(response => {
                if (response?.data?.success) {
                    // Replace the content inside '#wpbody' with the response HTML
                    $('#wpbody').empty().html(response?.data?.data?.form);

                    // Initialize Step Contents
                    initialStepContents();
                } else {
                    console.log(response.data);
                }
            }).catch( response => {
                console.log(response.data);
            });
    });
});

let totalStep = 3;
let currentStep = 1;
let directoryTitle = '';
let directoryLocation = '';
let directoryType = '';
let directoryPrompt = 'I want to create a car directory';
let maxPromptLength = 200;
let directoryKeywords = [];
let directoryFields = [];
let directoryPinnedFields = [];
let creationCompleted = false;

// Update Step Title
function updateStepTitle(title) {
    $('.directorist-create-directory__info__title').html(title);
}

// Update Step Description
function updateStepDescription(desc) {
    $('.directorist-create-directory__info__desc').html(desc);
}

// Update Button Text
function updateButtonText(text) {
    $('.directorist_generate_ai_directory .directorist_generate_ai_directory__text').html(text);
}

// Update Directory Prompt
function updatePrompt() {
    directoryPrompt = `I want to create a ${directoryType} directory${directoryLocation ? ` in ${directoryLocation}` : ''}`;
    $('#directorist-ai-prompt').val(directoryPrompt);
    $('#directorist-ai-prompt').siblings('.character-count').find('.current-count').text(directoryPrompt.length);
    if (directoryType) {
        handleCreateButtonEnable();
    } else {
        handleCreateButtonDisable();
    }
}

// Function to initialize Keyword Selected
function initializeKeyword() {
    const tagList = []; // Internal list for selected keywords
    const maxFreeTags = 5; // Max item limit for all users

    const tagListElem = document.getElementById("directorist-box__tagList");
    const newTagElem = document.getElementById("directorist-box__newTag");
    const recommendedTagsElem = document.getElementById("directorist-recommendedTags");
    const recommendedTags = Array.from(recommendedTagsElem.getElementsByTagName("li"));
    const tagLimitMsgElem = document.getElementById("directorist-tagLimitMsg");
    const tagCountElem = document.getElementById("directorist-tagCount");

    const canAddMoreTags = () => tagList.length < maxFreeTags;

    // Update the global keywords list
    const updateDirectoryKeywords = () => {
        directoryKeywords = [...tagList]; // Sync global keywords
    };

    // Update the tag count and recommended tags state
    const updateTagCount = () => {
        tagCountElem.textContent = `${tagList.length}/${maxFreeTags}`;
        tagLimitMsgElem.style.display = "flex";
        recommendedTagsElem.classList.toggle('recommend-disable', !canAddMoreTags());
    };

    // Update the recommended tags state based on the selected tags
    const updateRecommendedTagsState = () => {
        recommendedTags.forEach(tagElem => {
            const tagText = tagElem.textContent.trim();
            tagElem.classList.toggle('disabled', tagList.includes(tagText));
        });
    };

    // Render the tag list
    const renderTagList = () => {
        tagListElem.innerHTML = tagList.map(tag => (
            `<li>${tag} <span class="directorist-rmTag" style="cursor:pointer;">&times;</span></li>`
        )).join('');
        tagListElem.appendChild(newTagElem.parentNode || document.createElement('li').appendChild(newTagElem));

        updateRecommendedTagsState();
        updateTagCount();
        updateDirectoryKeywords();
    };

    // Add a new tag to the list
    const addTag = (tag) => {
        if (tag && !tagList.includes(tag) && canAddMoreTags()) {
            tagList.push(tag);
            renderTagList();
        }
    };

    // Remove a tag from the list
    const removeTag = (index) => {
        if (index !== -1) {
            tagList.splice(index, 1);
            renderTagList();
        }
    };

    // Event listener for adding tags via input
    newTagElem.addEventListener("keyup", (e) => {
        if (e.key === "Enter") {
            const newTag = newTagElem.value.trim();
            addTag(newTag);
            newTagElem.value = '';
        }
    });

    // Event delegation for removing tags
    tagListElem.addEventListener("click", (e) => {
        if (e.target.classList.contains("directorist-rmTag")) {
            const index = Array.from(tagListElem.children).indexOf(e.target.parentElement);
            removeTag(index);
        }
    });

    // Event listener for adding recommended tags
    recommendedTagsElem.addEventListener("click", (e) => {
        if (e.target.tagName === "LI" && !e.target.classList.contains("disabled")) {
            addTag(e.target.textContent.trim());
        }
    });

    // Initialize the tag management interface
    renderTagList();
}

// Function to initialize Progress bar
function initializeProgressBar(finalProgress) {
    if (finalProgress) {
        $('#directorist-create-directory__generating .directory-img #directory-img__generating').hide();
        $('#directorist-create-directory__generating .directory-img #directory-img__building').show();
        $('#directory-generate-btn__content__text').html('Generating directory...');
    } else {
        $('#directorist-create-directory__generating .directory-img #directory-img__generating').show();
        $('#directorist-create-directory__generating .directory-img #directory-img__building').hide();
    }
    const generateBtnWrapper = document.querySelector(".directory-generate-btn__wrapper");
    const btnPercentage = document.querySelector(".directory-generate-btn__percentage");
    const progressBar = document.querySelector(".directory-generate-btn--bg");

    if (generateBtnWrapper) {
        const finalWidth = generateBtnWrapper.getAttribute("data-width");

        let currentWidth = 0;
        
        const intervalDuration = 20; // Interval time in milliseconds
        const increment = finalWidth / (2000 / intervalDuration);

        // Update the progress bar width
        const updateProgress = () => {
            if (creationCompleted) {
                progressBar.style.width = `${finalWidth}%`;
                btnPercentage.textContent = '';
                $('#directory-generate-btn__content__text').html('Generated Successfully');
                if (typeof updateProgressList === 'function') {
                    updateProgressList(finalWidth);
                }

                clearInterval(progressInterval);
                return;
            } else if (currentWidth <= finalWidth) {
                btnPercentage.textContent = `${currentWidth}%`;
                progressBar.style.width = `${currentWidth}%`;

                if (typeof updateProgressList === 'function') {
                    updateProgressList(currentWidth);
                }

                currentWidth += increment;
            } else {
                if (!finalProgress) {
                    setTimeout(() => {
                        progressBar.style.width = '0';
                    }, 3000);
                }
                clearInterval(progressInterval);
            }
        };

        const progressInterval = setInterval(updateProgress, intervalDuration);
    }

    const steps = document.querySelectorAll(".directory-generate-progress-list li");

    // Update the progress list based on the current progress
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
    $('#directorist-create-directory__ai-fields .fields-count').html(dropdowns.length);

    const pinnedIconSVG = `
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.9189 3.03837C11.1788 2.43195 11.3088 2.12874 11.5188 1.99101C11.7024 1.87057 11.9262 1.82748 12.1414 1.87111C12.3875 1.921 12.6208 2.15426 13.0873 2.62078L17.3732 6.90673C17.8398 7.37325 18.073 7.60651 18.1229 7.85263C18.1665 8.06786 18.1234 8.29161 18.003 8.47524C17.8653 8.68523 17.5621 8.81517 16.9556 9.07507L14.877 9.96591C14.7888 10.0037 14.7447 10.0226 14.7034 10.0462C14.6667 10.0672 14.6316 10.0909 14.5985 10.1173C14.5612 10.1469 14.5273 10.1808 14.4594 10.2486L13.1587 11.5494C13.0526 11.6555 12.9995 11.7085 12.9574 11.769C12.92 11.8226 12.889 11.8805 12.8651 11.9414C12.8382 12.01 12.8235 12.0835 12.7941 12.2307L12.1833 15.2844C12.0246 16.078 11.9452 16.4748 11.736 16.6604C11.5538 16.8221 11.3099 16.896 11.0685 16.8625C10.7915 16.8241 10.5053 16.538 9.93307 15.9657L4.02829 10.0609C3.45602 9.48868 3.16989 9.20255 3.13148 8.9255C3.09802 8.68415 3.17187 8.44024 3.33359 8.25798C3.51923 8.04877 3.91602 7.96941 4.70961 7.8107L7.76333 7.19995C7.91047 7.17052 7.98403 7.15581 8.05264 7.1289C8.11353 7.10502 8.1714 7.07405 8.22505 7.03663C8.28549 6.99447 8.33854 6.94142 8.44465 6.83532L9.74539 5.53458C9.81322 5.46674 9.84714 5.43282 9.87676 5.39554C9.90307 5.36242 9.92681 5.32734 9.9478 5.29061C9.97142 5.24927 9.99031 5.20518 10.0281 5.117L10.9189 3.03837Z" fill="#141921"/>
            <path d="M6.98065 13.0133L2.2666 17.7274M9.74539 5.53458L8.44465 6.83532C8.33854 6.94142 8.28549 6.99447 8.22505 7.03663C8.1714 7.07405 8.11353 7.10502 8.05264 7.1289C7.98403 7.15581 7.91047 7.17052 7.76333 7.19995L4.70961 7.8107C3.91602 7.96941 3.51923 8.04877 3.33359 8.25798C3.17187 8.44024 3.09802 8.68415 3.13148 8.9255C3.16989 9.20255 3.45602 9.48868 4.02829 10.0609L9.93307 15.9657C10.5053 16.538 10.7915 16.8241 11.0685 16.8625C11.3099 16.896 11.5538 16.8221 11.736 16.6604C11.9452 16.4748 12.0246 16.078 12.1833 15.2844L12.7941 12.2307C12.8235 12.0835 12.8382 12.01 12.8651 11.9414C12.889 11.8805 12.92 11.8226 12.9574 11.769C12.9995 11.7085 13.0526 11.6555 13.1587 11.5494L14.4594 10.2486C14.5273 10.1808 14.5612 10.1469 14.5985 10.1173C14.6316 10.0909 14.6667 10.0672 14.7034 10.0462C14.7447 10.0226 14.7888 10.0037 14.877 9.96591L16.9556 9.07507C17.5621 8.81517 17.8653 8.68523 18.003 8.47524C18.1234 8.29161 18.1665 8.06786 18.1229 7.85263C18.073 7.60651 17.8398 7.37325 17.3732 6.90673L13.0873 2.62078C12.6208 2.15426 12.3875 1.921 12.1414 1.87111C11.9262 1.82748 11.7024 1.87057 11.5188 1.99101C11.3088 2.12874 11.1788 2.43195 10.9189 3.03837L10.0281 5.117C9.99031 5.20518 9.97142 5.24927 9.9478 5.29061C9.92681 5.32734 9.90307 5.36242 9.87676 5.39554C9.84714 5.43282 9.81322 5.46674 9.74539 5.53458Z" stroke="#141921" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    `;

    const unpinnedIconSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0616 1.29452C11.4288 1.05364 11.8763 0.967454 12.3068 1.05472C12.6318 1.12059 12.8801 1.29569 13.0651 1.44993C13.2419 1.59735 13.4399 1.79537 13.653 2.00849L17.9857 6.34116C18.1988 6.55424 18.3968 6.75223 18.5442 6.92908C18.6985 7.11412 18.8736 7.36242 18.9395 7.6874C19.0267 8.11785 18.9405 8.56535 18.6997 8.93261C18.5178 9.20988 18.263 9.3754 18.0511 9.48992C17.8485 9.59937 17.5911 9.70966 17.3141 9.82836L15.2051 10.7322C15.1578 10.7525 15.1347 10.7624 15.118 10.77C15.1176 10.7702 15.1173 10.7704 15.1169 10.7705C15.1166 10.7708 15.1163 10.7711 15.116 10.7714C15.1028 10.7841 15.0849 10.8018 15.0485 10.8382L13.7478 12.1389C13.6909 12.1959 13.6629 12.224 13.6432 12.2451C13.6427 12.2456 13.6423 12.2461 13.6418 12.2466C13.6417 12.2472 13.6415 12.2479 13.6414 12.2486C13.6347 12.2767 13.6268 12.3155 13.611 12.3944L12.9932 15.4835C12.92 15.8499 12.8541 16.1794 12.7773 16.438C12.7004 16.6969 12.5739 17.0312 12.289 17.2841C11.9244 17.6075 11.4366 17.7552 10.9539 17.6883C10.5765 17.636 10.2858 17.4279 10.0783 17.2552C9.87091 17.0826 9.63334 16.845 9.36915 16.5808L6.98049 14.1922L2.85569 18.317C2.53026 18.6424 2.00262 18.6424 1.67718 18.317C1.35175 17.9915 1.35175 17.4639 1.67718 17.1384L5.80198 13.0136L3.41338 10.625C3.14915 10.3608 2.91154 10.1233 2.73896 9.91588C2.56626 9.70833 2.3582 9.41765 2.30588 9.04027C2.23896 8.55756 2.38666 8.06974 2.7101 7.70522C2.96297 7.42025 3.29732 7.29379 3.55615 7.2169C3.81479 7.14007 4.14427 7.0742 4.51068 7.00094L7.59973 6.38313C7.67866 6.36735 7.71751 6.35946 7.7456 6.35282C7.74629 6.35266 7.74696 6.3525 7.7476 6.35234C7.74808 6.3519 7.74858 6.35143 7.7491 6.35095C7.7702 6.33126 7.79832 6.3033 7.85523 6.24639L9.15597 4.94565C9.19239 4.90923 9.21013 4.89143 9.22278 4.87819C9.22308 4.87787 9.22336 4.87757 9.22364 4.87729C9.22381 4.87692 9.22398 4.87654 9.22416 4.87615C9.23175 4.85949 9.24169 4.83641 9.26199 4.78906L10.1658 2.68009C10.2845 2.40306 10.3948 2.14567 10.5043 1.94311C10.6188 1.73117 10.7843 1.47638 11.0616 1.29452ZM10.5222 15.3768C10.82 15.6746 11.003 15.8565 11.1444 15.9741C11.1535 15.9817 11.162 15.9886 11.1699 15.995C11.173 15.9853 11.1762 15.9748 11.1796 15.9634C11.232 15.7871 11.2834 15.5343 11.366 15.1213L11.9767 12.0676C11.9787 12.0577 11.9808 12.0475 11.9828 12.037C12.0055 11.9222 12.0342 11.7776 12.0892 11.6374C12.1369 11.5156 12.1989 11.3999 12.2737 11.2926C12.3599 11.169 12.4643 11.065 12.5472 10.9825C12.5547 10.9749 12.5621 10.9676 12.5693 10.9604L13.87 9.6597C13.8746 9.65514 13.8793 9.65044 13.884 9.64564C13.9371 9.59249 14.0038 9.52556 14.08 9.46504C14.1462 9.41243 14.2164 9.36493 14.2899 9.32297C14.3743 9.2747 14.4613 9.23757 14.5303 9.20809C14.5366 9.20543 14.5427 9.20282 14.5486 9.20028L16.6272 8.30944C16.9451 8.17321 17.1311 8.09262 17.2588 8.02362C17.2656 8.01993 17.272 8.01642 17.2779 8.0131C17.2736 8.00783 17.269 8.00221 17.264 7.99624C17.1711 7.88475 17.0283 7.74085 16.7838 7.49631L12.4979 3.21037C12.2533 2.96583 12.1094 2.82309 11.9979 2.73014C11.992 2.72517 11.9864 2.72057 11.9811 2.71631C11.9778 2.72222 11.9743 2.72858 11.9706 2.73541C11.9016 2.86312 11.821 3.04909 11.6847 3.36696L10.7939 5.44559C10.7914 5.45153 10.7887 5.45762 10.7861 5.46387C10.7566 5.53289 10.7195 5.61984 10.6712 5.70432C10.6292 5.77777 10.5817 5.84793 10.5291 5.91417C10.4686 5.99036 10.4017 6.05713 10.3485 6.11013C10.3437 6.11492 10.339 6.1196 10.3345 6.12417L9.03374 7.4249C9.02658 7.43206 9.01924 7.43943 9.01172 7.44698C8.92915 7.52989 8.82513 7.63432 8.70159 7.72048C8.59429 7.79531 8.47855 7.85725 8.35677 7.90502C8.21655 7.96002 8.07196 7.98864 7.95717 8.01136C7.94673 8.01343 7.93652 8.01544 7.92659 8.01743L4.87287 8.62817C4.45985 8.71078 4.20704 8.7622 4.03076 8.81456C4.0194 8.81794 4.00891 8.82117 3.99923 8.82425C4.00557 8.83219 4.01251 8.84071 4.02009 8.84981C4.13771 8.99116 4.31954 9.17418 4.61738 9.47202L10.5222 15.3768Z" fill="currentColor"></path>
        </svg>
    `;

    // Initialize each dropdown
    dropdowns.forEach((dropdown) => {
        const header = dropdown.querySelector(".directorist-ai-generate-dropdown__header.has-options");
        const content = dropdown.querySelector(".directorist-ai-generate-dropdown__content");
        const icon = dropdown.querySelector(".directorist-ai-generate-dropdown__header-icon");
        const pinIcon = dropdown.querySelector(".directorist-ai-generate-dropdown__pin-icon");
        const dropdownItem = dropdown.closest('.directorist-ai-generate-box__item');

        // Pin Field
        pinIcon.addEventListener("click", (event) => {
            event.stopPropagation();
            if (dropdownItem.classList.contains("pinned")) {
                dropdownItem.classList.remove("pinned");
                dropdownItem.classList.add("unpinned");

                // Change to pinned SVG
                pinIcon.innerHTML = unpinnedIconSVG;

            } else {
                dropdownItem.classList.remove("unpinned");
                dropdownItem.classList.add("pinned");

                // Change to pinned SVG
                pinIcon.innerHTML = pinnedIconSVG;

            }

            // Find all pinned items
            directoryPinnedFields = findAllPinnedItems();
        });

        // Toggle the dropdown content
        header && header.addEventListener("click", (event) => {
            if (event.target === pinIcon || pinIcon.contains(event.target)) {
                return;
            }

            const isExpanded = content && content.classList.toggle("directorist-ai-generate-dropdown__content--expanded");
            dropdown.setAttribute("aria-expanded", isExpanded);
            content.setAttribute("aria-expanded", isExpanded);
            icon.classList.toggle("rotate", isExpanded);

            if (accordion) {
                dropdowns.forEach((otherDropdown) => {
                    if (otherDropdown !== dropdown) {
                        const otherContent = otherDropdown.querySelector(".directorist-ai-generate-dropdown__content");
                        const otherIcon = otherDropdown.querySelector(".directorist-ai-generate-dropdown__header-icon");
                        otherDropdown.setAttribute("aria-expanded", false);

                        if (otherContent) {
                            otherContent.classList.remove("directorist-ai-generate-dropdown__content--expanded");
                            otherContent.setAttribute("aria-expanded", false);
                        }
                        if (otherIcon) {
                            otherIcon.classList.remove("rotate");
                        }
                    }
                });
            }
        });
    });


    // Function to find all pinned items
    function findAllPinnedItems() {
        const pinnedElements = document.querySelectorAll('.directorist-ai-generate-box__item.pinned');
        if (pinnedElements.length > 0) {
            const titles = Array.from(pinnedElements).flatMap(pinnedElement =>
                Array.from(pinnedElement.querySelectorAll('.directorist-ai-generate-dropdown__title-main h6'))
                .map(item => item.innerText)
            );
            return titles;  // Return the array of titles
        }
        return [];
    }

}

// Function to handle back button
function handleBackButton() {
    currentStep = 1;
    // Back to initial step
    initialStepContents();
}

// handle back btn
$('body').on( 'click', '.directorist-create-directory__back__btn', function( e ) {
    e.preventDefault();
    handleBackButton();
});

// Enable Submit Button
function handleCreateButtonEnable() {
    $('.directorist_generate_ai_directory').removeClass('disabled');
}

// Disable Submit Button
function handleCreateButtonDisable() {
    $('.directorist_generate_ai_directory').addClass('disabled');
}

// Initial Step Contents
function initialStepContents() {
    // Hide all steps except the first one initially
    $('#directorist-create-directory__creating').hide();
    $('#directorist-create-directory__ai-fields').hide();
    $('#directorist-create-directory__generating').hide();
    $('.directorist-create-directory__content__items').hide();
    $('.directorist-create-directory__back__btn').addClass('disabled');
    $('.directorist-create-directory__content__items[data-step="1"]').show();
    $('.directorist-create-directory__step .step-count .total-step').html(totalStep);
    $('.directorist-create-directory__step .step-count .current-step').html(1);
    $('#directorist-ai-prompt').siblings('.character-count').find('.max-count').text(maxPromptLength);

    const $directoryName = $('.directorist-create-directory__content__input[name="directory-name"]');
    const $directoryLocation = $('.directorist-create-directory__content__input[name="directory-location"]');

    if(!$directoryName.val()) {
        handleCreateButtonDisable();
        directoryTitle= '';
    }

    if(!$directoryLocation.val()) {
        directoryLocation= '';
    }

    // Directory Title Input Listener
    $directoryName.on( 'input', function(e) {
        directoryTitle = $(this).val();
        if (directoryTitle) {
            handleCreateButtonEnable();
            updatePrompt();
        } else {
            handleCreateButtonDisable();
        }
    });

    // Directory Location Input Listener
    $directoryLocation.on('input', function(e) {
        directoryLocation = $(this).val();
        updatePrompt();
    });

    // Directory Prompt Input Listener
    $('body').on('input keyup', '#directorist-ai-prompt', function(e) {
        $('#directorist-ai-prompt').siblings('.character-count').find('.current-count').text(directoryPrompt.length);
        if (e.target.value.length > maxPromptLength) {
            // Limit to maxPromptLength characters by preventing additional input
            e.target.value = e.target.value.substring(0, maxPromptLength);

            // Add a class to indicate the maximum character limit reached
            $(e.target).addClass('max-char-reached');
        } else {
            // Remove the class if below the maximum character limit
            $(e.target).removeClass('max-char-reached');
        }
        
        if (!e.target.value) {
            directoryPrompt = '';
            handleCreateButtonDisable();
        } else {
            directoryPrompt = e.target.value;
            handleCreateButtonEnable();
        }
    });

    // Other Directory Type Input Listener
    function checkOtherDirectoryType(type) {
        updatePrompt();
        if (type === '') {
            handleCreateButtonDisable();
            $('#new-directory-type').addClass('empty');
        } else {
            handleCreateButtonEnable();
            $('#new-directory-type').removeClass('empty');
        }
    }

    // Check if any item is initially checked
    $('[name="directory_type[]"]').each(function() {
        if ($(this).is(':checked')) {
            directoryType = $(this).val();
        }
    });

    // Directory Type Input Listener
    $('body').on('change', '[name="directory_type[]"]', function(e) {
        directoryType = e.target.value;
        // Show or hide the input based on the selected value
        if (directoryType === 'others') {
            directoryType = $('#new-directory-type').val();
            $('#directorist-create-directory__checkbox__others').show();
            checkOtherDirectoryType(directoryType);
            $('#new-directory-type').focus();
            $('body').on('input', '[name="new-directory-type"]', function(e) {
                directoryType = e.target.value;
                checkOtherDirectoryType(directoryType);
            });
        } else {
            $('#directorist-create-directory__checkbox__others').hide();
            updatePrompt();
        }
    });


}

// Handle Prompt Step
function handlePromptStep(response) {
    $('.directorist-create-directory__content__items[data-step="2"]').hide();
    $('.directorist-create-directory__content__items[data-step="3"]').show();
    $('.directorist-create-directory__back__btn').hide();
    $('#directorist-recommendedTags').empty().html(response);
    initializeKeyword();

    updateStepTitle('Select relevant keywords to <br /> optimize AI-generated content');
    updateStepDescription('Keywords helps AI to generate relevant categories and fields');
    updateButtonText('Generate Directory');

    currentStep = 3;
}

// Handle Keyword Step
function handleKeywordStep() {
    $('#directorist-create-directory__generating').show();
    $('.directorist-create-directory__top').hide();
    $('.directorist-create-directory__content__items').hide();
    $('.directorist-create-directory__header').hide();
    $('.directorist-create-directory__content__footer').hide();
    $('.directorist-create-directory__content').toggleClass('full-width');

    updateButtonText('Build Directory');

    initializeProgressBar();
}

// Handle Generated Fields
function handleGenerateFields(response) {
    $('#directorist-create-directory__ai-fields').show();
    $('.directorist-create-directory__header').show();
    $('.directorist_regenerate_fields').show();
    $('#directorist-create-directory__generating').hide();
    $('.directorist-create-directory__content__footer').show();
    $('.directorist-create-directory__content').removeClass('full-width');

    $('#directorist-ai-generated-fields-array').val( JSON.stringify( response?.data?.fields ))
    $('#directorist_ai_generated_fields').empty().html(response);

    initializeDropdownField();
    currentStep = 4;
}

// Handle Create Directory
function handleCreateDirectory( redirect_url ) {
    $('#directorist-create-directory__preview-btn').removeClass('disabled');
    $('#directorist-create-directory__preview-btn').attr('href', redirect_url );
    $('#directorist-create-directory__generating .directory-title').html('Your directory is ready to use');
    creationCompleted = true;
}

// Response Success Callback
function handleAIFormResponse(response) {
    if (response?.data?.success) {
        let nextStep = currentStep + 1;

        $('.directorist-create-directory__content__items[data-step="' + currentStep + '"]').hide();
        $('.directorist-create-directory__step .step-count .current-step').html(nextStep);
        $(`.directorist-create-directory__step .atbdp-setup-steps li:nth-child(${nextStep})`).addClass('active');
        if ($('.directorist-create-directory__content__items[data-step="' + nextStep + '"]').length) {
            $('.directorist-create-directory__content__items[data-step="' + nextStep + '"]').show();
        }

        if (currentStep == 2) {
            handlePromptStep(response?.data?.data?.html);
        } else if (currentStep == 3) {
            setTimeout(() => {
                handleGenerateFields(response?.data?.data?.html);
            }, 1000);
            directoryFields = JSON.stringify(response?.data?.data?.fields );
        } else if (currentStep == 4) {
            handleCreateDirectory( response?.data?.data?.url );
        }
    } else {
        console.error(response?.data);
    }
};

// Generate AI Directory Form Submission Handler
$('body').on('click', '.directorist_generate_ai_directory', function(e) {
    e.preventDefault();

    if (currentStep == 1) {
        $('.directorist-create-directory__back__btn').removeClass('disabled');
        $('.directorist-create-directory__content__items[data-step="1"]').hide();
        $('.directorist-create-directory__content__items[data-step="2"]').show();
        $('.directorist-create-directory__step .step-count .current-step').html(2);
        $(`.directorist-create-directory__step .atbdp-setup-steps li:nth-child(2)`).addClass('active');

        updateStepTitle('Describe your business in plain language');
        currentStep = 2;
        return;
    } else if (currentStep == 3) {
        handleKeywordStep();
    } else if (currentStep == 4) {
        $('#directorist-create-directory__generating').show();
        $('#directorist-create-directory__creating').show();
        $('#directorist-create-directory__ai-fields').hide();
        $('.directorist_regenerate_fields').hide();
        $('.directorist-create-directory__top').hide();
        $('.directorist-create-directory__content__items').hide();
        $('.directorist-create-directory__header').hide();
        $('.directorist-create-directory__content__footer').hide();
        $('.directorist-create-directory__content').addClass('full-width');
        $('#directorist-create-directory__preview-btn').addClass('disabled');

        $('#directorist-create-directory__generating .directory-title').html('Directory AI is Building your directory... ');
        $('#directorist-create-directory__generating .directory-description').html('We\'re using your infomation to finalize your directory fields.');

        initializeProgressBar('finalProgress');
    }

    handleCreateButtonDisable();

    let form_data = new FormData();
    form_data.append('action', 'directorist_ai_directory_creation');
    form_data.append('name', directoryTitle);
    form_data.append('prompt', directoryPrompt);
    form_data.append('keywords', directoryKeywords);
    form_data.append('fields', directoryFields);
    form_data.append('step', currentStep - 1);

    // Handle Axios Request
    axios.post(directorist_admin.ajax_url, form_data)
        .then(response => {
            handleCreateButtonEnable();
            handleAIFormResponse(response);
        })
        .catch(error => {
            if (error.response.data?.success === false && error.response.data?.data?.code === 'limit_exceeded') {
                alert('🙌 You\'ve exceeded the request/site beta limit.');
            }

            handleCreateButtonEnable();
            console.error(error.response.data);
        });
});


// Regenerate Fields
$('body').on('click', '.directorist_regenerate_fields', function(e) {
    e.preventDefault();
    $(this).addClass('loading');

    let form_data = new FormData();
    form_data.append('action', 'directorist_ai_directory_creation');
    form_data.append('name', directoryTitle);
    form_data.append('prompt', directoryPrompt);
    form_data.append('keywords', directoryKeywords);
    form_data.append('pinned', directoryPinnedFields);
    form_data.append('step', 2);

    // Handle Axios Request
    axios.post(directorist_admin.ajax_url, form_data)
        .then(response => {
            $(this).removeClass('loading');
            handleGenerateFields(response?.data?.data?.html);
            $('.directorist_regenerate_fields').hide();
            directoryFields = JSON.stringify( response.data.data.fields );
        })
        .catch(error => {
            if (error.response.data?.success === false && error.response.data?.data?.code === 'limit_exceeded') {
                alert('🙌 You\'ve exceeded the request/site beta limit.');
            }

            $(this).removeClass('loading');
            console.error(error.response.data);
        });
});

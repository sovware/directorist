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

            responseFieldCallback( response );
        };

        // Response Error Callback
        const responseFieldCallback = function ( response ) {

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
        let form_data = new FormData();
        form_data.append('action', 'directorist_ai_directory_form');

        // Success callback to handle the response
        function handleAIFormInit(response) {
            if (response?.data?.success) {
                // Replace the content inside '#wpbody' with the response HTML
                $('#wpbody').empty().html(response?.data?.html);

                // Initialize Step Contents
                initialStepContents(); 
                return;
            }

            // Show an error message if the request was not successful
            alert('Initi Something went wrong! Please try again');
        };

        // Send the request using Axios
        axios.post(directorist_admin.ajax_url, form_data)
            .then(response => {
                handleAIFormInit(response);  // Handle the response
            })
    });
}); 

let totalStep = 3;
let currentStep = 1;
let directoryTitle = '';
let directoryLocation = '';
let directoryType = '';
let directoryPrompt = 'I want to create a car directory';
let directoryKeywords = [];
let directoryFields = [];
let directoryPinnedFields = [];


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
    directoryPrompt = `I want to create a ${directoryType || 'car'} directory${directoryLocation ? ` in ${directoryLocation}` : ''}`;
    $('#directorist-ai-prompt').html(directoryPrompt);
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
function initializeProgressBar() {
    const generateBtnWrapper = document.querySelector(".directory-generate-btn__wrapper");

    if (generateBtnWrapper) {
        const finalWidth = generateBtnWrapper.getAttribute("data-width");
        const btnPercentage = document.querySelector(".directory-generate-btn__percentage");
        const progressBar = document.querySelector(".directory-generate-btn--bg");

        let currentWidth = 0;

        // Update the progress bar width
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

    // Initialize each dropdown
    dropdowns.forEach((dropdown) => {
        const header = dropdown.querySelector(".directorist-ai-generate-dropdown__header");
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
            } else {
                dropdownItem.classList.remove("unpinned");
                dropdownItem.classList.add("pinned");
            }
        
            // Find all pinned items
            directoryPinnedFields = findAllPinnedItems();
        });

        // Toggle the dropdown content
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
    $('.directorist-create-directory__content__items[data-step="1"]').show();
    $('.directorist-create-directory__step .step-count .total-step').html(totalStep);
    $('.directorist-create-directory__step .step-count .current-step').html(1);
    $('.directorist-create-directory__back__btn').addClass('disabled');

    const $directoryName = $('.directorist-create-directory__content__input[name="directory-name"]');
    const $directoryLocation = $('.directorist-create-directory__content__input[name="directory-location"]');
    
    if(!$directoryName.val()) {
        handleCreateButtonDisable()
    }

    // Directory Title Input Listener
    $directoryName.on( 'keyup change ', function( e ) {
        directoryTitle = $(this).val();
        
        if (directoryTitle) {
            handleCreateButtonEnable();
        } else {
            handleCreateButtonDisable();
        }
    });
    
    // Directory Location Input Listener
    $directoryLocation.on('keyup change', function(e) {
        directoryLocation = $(this).val();
        updatePrompt();
    });
    
    // Directory Location Input Listener
    $('body').on('keyup change', '#directorist-ai-prompt', function(e) {
        directoryPrompt = e.target.value;
    });

    // Directory Type Input Listener
    $('body').on('change', '[name="directory_type[]"]', function(e) {
        directoryType = e.target.value;
        // Show or hide the input based on the selected value
        if (directoryType === 'others') {
            directoryType = $('#new-directory-type').val();
            $('#directorist-create-directory__checkbox__others').show();
            $('body').on('keyup', '[name="new-directory-type"]', function(e) {
                directoryType = e.target.value;
                updatePrompt();
            });
        } else {
            $('#directorist-create-directory__checkbox__others').hide();
        }

        updatePrompt();
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
function handleCreateDirectory() {    
    $('#directorist-create-directory__generating').show();
    $('#directorist-create-directory__creating').show();
    $('#directorist-create-directory__ai-fields').hide();
    $('.directorist_regenerate_fields').hide();
    $('.directorist-create-directory__top').hide();
    $('.directorist-create-directory__content__items').hide();
    $('.directorist-create-directory__header').hide();
    $('.directorist-create-directory__content__footer').hide();
    $('.directorist-create-directory__content').addClass('full-width');

    $('#directorist-create-directory__generating .directory-title').html('Directory AI is Building your directory... ');
    $('#directorist-create-directory__generating .directory-description').html('We\'re using your infomation to finalize your directory fields.');
    
    initializeProgressBar();

    $('#directorist-create-directory__preview-btn').attr('href', 'https://www.directorist.com');
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
            handlePromptStep(response?.data?.html);
        } else if (currentStep == 3) {
            handleGenerateFields(response?.data?.html);
            directoryFields = JSON.stringify( response?.data?.fields );
        } else if (currentStep == 4) {
            handleCreateDirectory();
        }

        return;
    } else {
        console.error('Something went wrong! Please try again');
    }
}; 

// Generate AI Directory Form Submission Handler
$('body').on('click', '.directorist_generate_ai_directory', function(e) {
    e.preventDefault();

    if (currentStep == 1) {
        $('.directorist-create-directory__content__items[data-step="1"]').hide();
        $('.directorist-create-directory__content__items[data-step="2"]').show();
        $('.directorist-create-directory__back__btn').removeClass('disabled');
        $('.directorist-create-directory__step .step-count .current-step').html(2);
        $(`.directorist-create-directory__step .atbdp-setup-steps li:nth-child(2)`).addClass('active');

        updateStepTitle('Describe your business in plain language');
        currentStep = 2;
        return;
    } else if (currentStep == 3) {
        handleKeywordStep();
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
            handleCreateButtonEnable();
            console.error(error);
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
            handleGenerateFields(response?.data?.html);
        })
        .catch(error => {
            $(this).removeClass('loading');
            console.error(error);
        });
});


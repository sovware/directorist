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


    // Show the form when the '.directorist-ai-directory-creation' element is clicked
    $('.directorist-ai-directory-creation').on('click', function(e) {
        e.preventDefault();

        // Prepare form data for the request
        let form_data = new FormData();
        form_data.append('action', 'directorist_ai_directory_form');

        // Success callback to handle the response
        const responseAIFormSuccess = function(response) {
            if (response?.data?.success) {
                // Replace the content inside '#wpbody' with the response HTML
                $('#wpbody').empty().html(response?.data?.html);
                console.log('Form Loaded Successfully');

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
                console.log('@Response Successfully', response);
                responseAIFormSuccess(response);  // Handle the response
            })
    });


});

let totalStep = 3;
let currentStep = 1;
let currentStepTitle = '';
let currentStepDesc = '';
let directoryTitle = '';
let directoryLocation = '';
let directoryType = '';
let directoryPrompt = '';
let directoryKeywords = [];

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

    const updateDirectoryKeywords = () => {
        directoryKeywords = [...tagList]; // Sync global keywords
        console.log('Updated directoryKeywords:', directoryKeywords);
    };

    const updateTagCount = () => {
        tagCountElem.textContent = `${tagList.length}/${maxFreeTags}`;
        tagLimitMsgElem.style.display = "flex";
        recommendedTagsElem.classList.toggle('recommend-disable', !canAddMoreTags());
    };

    const updateRecommendedTagsState = () => {
        recommendedTags.forEach(tagElem => {
            const tagText = tagElem.textContent.trim();
            tagElem.classList.toggle('disabled', tagList.includes(tagText));
        });
    };

    const renderTagList = () => {
        tagListElem.innerHTML = tagList.map(tag => (
            `<li>${tag} <span class="directorist-rmTag" style="cursor:pointer;">&times;</span></li>`
        )).join('');
        tagListElem.appendChild(newTagElem.parentNode || document.createElement('li').appendChild(newTagElem));

        updateRecommendedTagsState();
        updateTagCount();
        updateDirectoryKeywords();
    };

    const addTag = (tag) => {
        if (tag && !tagList.includes(tag) && canAddMoreTags()) {
            tagList.push(tag);
            renderTagList();
        }
    };

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

// Function to handle back button
// function handleBackButton() {
//     console.log('Handle Back Button', currentStep);
// }

// Enable Submit Button
function handleEnableButton() {
    $('.directorist_generate_ai_directory').removeClass('disabled');
}

// Disable Submit Button
function handleDisableButton() {
    $('.directorist_generate_ai_directory').addClass('disabled');
}

// Initial Step Contents
function initialStepContents() {
    console.log('Initial Step Contents', currentStep);
    // Hide all steps except the first one initially
    $('#directorist-create-directory__creating').hide();
    $('#directorist-create-directory__ai-fields').hide();
    $('#directorist-create-directory__generating').hide();
    $('.directorist-create-directory__content__items').hide();
    $('.directorist-create-directory__content__items[data-step="1"]').show();
    $('.directorist-create-directory__step .step-count .current-step').html(1);
    handleDisableButton()


    $('body').on( 'keyup change ', '.directorist-create-directory__content__input[name="directory-name"]', function( e ) {
        directoryTitle = e.target.value;
        console.log('directoryTitle Changed', directoryTitle);
        
        if (directoryTitle) {
            handleEnableButton();
        } else {
            handleDisableButton();
        }
    });
    
    // Directory Location Input Listener
    $('body').on('keyup change', '.directorist-create-directory__content__input[name="directory-location"]', function(e) {
        directoryLocation = e.target.value;
        console.log('directoryLocation Changed', directoryLocation);
        updatePrompt();
    });

    // Directory Type Input Listener
    $('body').on('change', '[name="directory_type[]"]', function(e) {
        directoryType = e.target.value;
        console.log('directoryType Changed', directoryType);
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

    // Generate AI Directory Button Click Handler
    $('body').on('click', '.directorist_generate_ai_directory', function(e) {
        e.preventDefault();
        if (currentStep === 1) {
            $('.directorist-create-directory__content__items[data-step="1"]').hide();
            $('.directorist-create-directory__content__items[data-step="2"]').show();
             
            $('.directorist-create-directory__step .step-count .current-step').html(2);
            $(`.directorist-create-directory__step .atbdp-setup-steps li:nth-child(2)`).addClass('active');

            currentStep = 2;
        }
    });
}

// Handle Step One 
function handlePromptStep(response) {
    console.log('handlePromptStep', currentStep, response);

    $('.directorist-create-directory__content__items[data-step="2"]').hide();
    $('.directorist-create-directory__content__items[data-step="3"]').show();
    initializeKeyword();
    $('#directorist-recommendedTags').empty().html(response);
    currentStep = 3;
}

// Handle Step Two
function handleKeywordStep(response) {
    console.log('handleKeywordStep', currentStep, response);
    
    $('#directorist-create-directory__generating').show();
    $('.directorist-create-directory__top').hide();
    $('.directorist-create-directory__content__items').hide();
    $('.directorist-create-directory__header').hide();
    $('.directorist-create-directory__content__footer').hide();
    $('.directorist-create-directory__content').toggleClass('full-width');
    
    initializeProgressBar();

    setTimeout(() => {
        $('#directorist-create-directory__ai-fields').show();
        $('.directorist-create-directory__header').show();
        $('#directorist-create-directory__generating').hide();
        $('.directorist-create-directory__content__footer').show();
        $('.directorist-create-directory__content').toggleClass('full-width');

        $('#directorist-ai-generate-box__fields').empty().html(response);

        initializeDropdownField();
    }, 3000);
    currentStep = 4;
}


// Response Success Callback
const responseAIFormSuccess = function(response) {
    if (response?.data?.success) {
        let nextStep = currentStep + 1;

        console.log('Response Success:', currentStep);
        $('.directorist-create-directory__content__items[data-step="' + currentStep + '"]').hide(); 
        $('.directorist-create-directory__step .step-count .current-step').html(nextStep);
        $(`.directorist-create-directory__step .atbdp-setup-steps li:nth-child(${nextStep})`).addClass('active');
        
        if ($('.directorist-create-directory__content__items[data-step="' + nextStep + '"]').length) {
            $('.directorist-create-directory__content__items[data-step="' + nextStep + '"]').show();
        }

        if (currentStep == 2) {
            handlePromptStep(response?.data?.html);
        } else if (currentStep == 3) {
            handleKeywordStep(response?.data?.html);
        } 

        return;
    }

    alert('Something went wrong! Please try again');
};


// handle back btn
$('body').on( 'click', '.directorist-create-directory__back__btn', function( e ) {
    e.preventDefault();
    // handleBackButton();
});

// handle axios request
function handleAxiosRequest(step) {
    console.log('handleAxiosRequest', step);

    if (step === 1) {
        return;
     }
 
     handleDisableButton();
 
     let form_data = new FormData();
     form_data.append('action', 'directorist_ai_directory_creation');
     form_data.append('prompt', directoryPrompt);
     form_data.append('keywords', directoryKeywords);
     form_data.append('step', step - 1);
 
     axios.post(directorist_admin.ajax_url, form_data)
         .then(response => {
             handleEnableButton();
             responseAIFormSuccess(response);
         })
         .catch(error => {
             handleEnableButton();
             console.log(error);
             alert('Something went wrong! Please try again');
         });
}

// Form Submission Handler
$('body').on('click', '.directorist_generate_ai_directory', function(e) {
    e.preventDefault();

    // Handle Axios Request
    handleAxiosRequest(currentStep);
});


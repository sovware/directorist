
function directoristModalAlert(data){

    let alertModalElement = document.querySelector('.directorist-modal-alert');
    alertModalElement.classList.add('directorist-show');

    let iconClass = ""

    if(data.type == "error"){
        iconClass = "la la-times"
    }else if(data.type == "warning"){
        iconClass = "la la-exclamation"
    }else if(data.type == "success"){
        iconClass = "la la-check"
    }

    if(data.text === undefined){
        data.text = "";
    }

    let alertModalContent = `
        <div class="directorist-modal__dialog">

            <div class="directorist-modal__content">

                <div class="directorist-modal__body directorist-text-center directorist-modal-alert-${data.type}">

                    <div class="directorist-modal-alert-icon">

                        <i class="${iconClass}"></i>

                    </div>

                    <div class="directorist-modal-alert-text">

                        <h3 class="directorist-modal-alert-text__title directorist-text-${data.type}">${data.title}</h3>
                        
                        <p class="directorist-modal-alert-text__details">${data.text}</p>

                    </div>
                    
                </div>

                <div class="directorist-modal__footer directorist-text-center${data.action ? ' directorist-modal-alert-action':'' }">

					<button class="directorist-btn directorist-btn-danger directorist-modal-close directorist-modal-close-js">Cancel</button>

					<button class="directorist-btn directorist-btn-info directorist-modal-close-js" id="${data.okButtonUniqueId}">Yes, Delete It!</button>

				</div>

            </div>

        </div>
    `;

    alertModalElement.innerHTML = alertModalContent;

    if(data.timeout){
        setTimeout(function(){ 
            alertModalElement.classList.remove('directorist-show');
         }, data.timeout);
    }
    
}

export { directoristModalAlert };
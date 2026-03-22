const $ = jQuery;

$(document).ready(function () {
	modalToggle();
});

function modalToggle() {
	$('.atbdp_recovery_pass').on('click', function (e) {
		e.preventDefault();
		$('#recover-pass-modal').slideToggle().show();
	});

	// Contact form [on modal closed]
	$('#atbdp-contact-modal').on('hidden.bs.modal', function (e) {
		$('#atbdp-contact-message').val('');
		$('#atbdp-contact-message-display').html('');
	});

	// Template Restructured
	// Modal
	// Store original parent references for modals
	const modalOriginalParents = new Map();

	// Function to restore modal to its original position
	function restoreModalToOriginalPosition(modalElement) {
		if (!modalElement || !modalOriginalParents.has(modalElement)) {
			return;
		}

		var originalData = modalOriginalParents.get(modalElement);
		var originalParent = originalData.parent;

		// Only restore if original parent still exists in the DOM
		if (originalParent && document.body.contains(originalParent)) {
			// Restore to original position using nextSibling if available
			if (
				originalData.nextSibling &&
				originalParent.contains(originalData.nextSibling)
			) {
				originalParent.insertBefore(
					modalElement,
					originalData.nextSibling
				);
			} else {
				originalParent.appendChild(modalElement);
			}
		}
	}

	$('body').on('click', '.directorist-btn-modal-js', function (e) {
		e.preventDefault();
		var data_target = $(this).attr('data-directorist_target');
		var modalElement = document.querySelector(`.${data_target}`);

		if (!modalElement) {
			return;
		}

		// Move modal to body if not already a direct child of body
		if (modalElement.parentElement !== document.body) {
			// Store original parent if not already stored
			if (!modalOriginalParents.has(modalElement)) {
				modalOriginalParents.set(modalElement, {
					parent: modalElement.parentElement,
					nextSibling: modalElement.nextSibling,
				});
			}

			// Move to body
			document.body.appendChild(modalElement);
		}

		// Show modal
		modalElement.classList.add('directorist-show');
	});

	$('body').on('click', '.directorist-modal-close-js', function (e) {
		e.preventDefault();
		var modalElement = $(this).closest('.directorist-modal-js');

		// Hide modal
		modalElement.removeClass('directorist-show');

		// Restore to original position
		restoreModalToOriginalPosition(modalElement[0]);
	});

	// Close modal when clicking backdrop (not content inside)
	$('body').on('click', '.directorist-modal-js', function (e) {
		// Only close if clicking the backdrop itself, not children
		if (e.target === this && $(this).hasClass('directorist-show')) {
			$(this).removeClass('directorist-show');
			restoreModalToOriginalPosition(this);
		}
	});
}

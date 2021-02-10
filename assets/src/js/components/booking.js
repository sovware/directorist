// Booking Available Time
const flatWrapper = document.querySelector(".flatpickr-calendar");
const fAvailableTime = document.querySelector(".bdb-available-time-wrapper");

if (flatWrapper != null && fAvailableTime != null) {
    flatWrapper.insertAdjacentElement("beforeend", fAvailableTime);
}
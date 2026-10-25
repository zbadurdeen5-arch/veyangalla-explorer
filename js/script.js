

document.addEventListener("DOMContentLoaded", function () {

    const alerts = document.querySelectorAll(".auto-hide");

    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.display = "none";
        }, 5000);
    });

    const deleteButtons = document.querySelectorAll(".delete-confirm");

    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {

            const confirmed = confirm(
                "Are you sure you want to delete this attraction?"
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    });

});
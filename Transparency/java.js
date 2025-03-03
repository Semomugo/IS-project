document.addEventListener("DOMContentLoaded", function() {
    console.log("Website Loaded Successfully!");

    const donateButton = document.querySelector(".donate-btn");

    donateButton.addEventListener("click", function(event) {
        event.preventDefault();
        alert("Thank you for your willingness to donate! This feature will be available soon.");
    });
});
document.querySelector('.quote').addEventListener('click', function() {
    alert('Get a Quote Clicked!');
});

document.querySelector('.contact').addEventListener('click', function() {
    alert('Contact Us Clicked!');
});

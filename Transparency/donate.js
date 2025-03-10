document.addEventListener("DOMContentLoaded", () => {
  // Form validation
  const donationForm = document.getElementById("donationForm");
  const customAmountContainer = document.getElementById("customAmountContainer");
  const amountOptions = document.querySelectorAll('input[name="amount"]');
  
  // Show custom amount input when custom option is selected
  amountOptions.forEach(option => {
    option.addEventListener("change", function() {
      if (this.value === "custom") {
        customAmountContainer.style.display = "block";
      } else {
        customAmountContainer.style.display = "none";
      }
    });
  });
  
  // Form submission
  donationForm.addEventListener("submit", function(e) {
    e.preventDefault();
    
    // Reset error messages
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach(message => {
      message.textContent = "";
    });
    
    let isValid = true;
    
    // Validate first name
    const firstName = document.getElementById("firstName");
    if (firstName.value.trim() === "") {
      document.getElementById("firstNameError").textContent = "First name is required";
      isValid = false;
    }
    
    // Validate last name
    const lastName = document.getElementById("lastName");
    if (lastName.value.trim() === "") {
      document.getElementById("lastNameError").textContent = "Last name is required";
      isValid = false;
    }
    
    // Validate email
    const email = document.getElementById("email");
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
      document.getElementById("emailError").textContent = "Please enter a valid email address";
      isValid = false;
    }
    
    // Validate phone
    const phone = document.getElementById("phone");
    if (phone.value.trim() === "") {
      document.getElementById("phoneError").textContent = "Phone number is required";
      isValid = false;
    }
    
    // Validate country
    const country = document.getElementById("country");
    if (country.value === "") {
      document.getElementById("countryError").textContent = "Please select a country";
      isValid = false;
    }
    
    // Validate state
    const state = document.getElementById("state");
    if (state.value.trim() === "") {
      document.getElementById("stateError").textContent = "State/Province is required";
      isValid = false;
    }
    
    // Validate address
    const address = document.getElementById("address");
    if (address.value.trim() === "") {
      document.getElementById("addressError").textContent = "Address is required";
      isValid = false;
    }
    
    // Validate donation amount
    let selectedAmount = false;
    let donationAmount = 0;
    
    amountOptions.forEach(option => {
      if (option.checked) {
        selectedAmount = true;
        if (option.value === "custom") {
          const customAmount = document.getElementById("customAmount");
          if (customAmount.value.trim() === "" || isNaN(customAmount.value) || Number(customAmount.value) <= 0) {
            document.getElementById("amountError").textContent = "Please enter a valid donation amount";
            isValid = false;
          } else {
            donationAmount = Number(customAmount.value);
          }
        } else {
          donationAmount = Number(option.value);
        }
      }
    });
    
    if (!selectedAmount) {
      document.getElementById("amountError").textContent = "Please select a donation amount";
      isValid = false;
    }
    
    // If form is valid, show success message
    if (isValid) {
      // In a real application, you would submit the form data to a server here
      // For this example, we'll just show a success message
      
      // Create success message
      const successMessage = document.createElement("div");
      successMessage.className = "success-message";
      successMessage.innerHTML = `
        <h3>Thank You for Your Donation!</h3>
        <p>Dear ${firstName.value}, thank you for your generous donation of $${donationAmount}.</p>
        <p>A confirmation email has been sent to ${email.value}.</p>
        <p>Your support helps us make a difference in communities worldwide.</p>
      `;
      
      // Hide the form and show success message
      donationForm.style.display = "none";
      donationForm.parentNode.appendChild(successMessage);
      successMessage.style.display = "block";
      
      // Scroll to success message
      successMessage.scrollIntoView({ behavior: "smooth" });
    }
  });
  
  // FAQ accordion functionality
  const faqItems = document.querySelectorAll(".faq-item");
  
  faqItems.forEach(item => {
    const question = item.querySelector(".faq-question");
    
    question.addEventListener("click", () => {
      // Close all other items
      faqItems.forEach(otherItem => {
        if (otherItem !== item && otherItem.classList.contains("active")) {
          otherItem.classList.remove("active");
        }
      });
      
      // Toggle current item
      item.classList.toggle("active");
    });
  });
  
  // Add animation to donation form and info sections
  const donationInfo = document.querySelector(".donation-info");
  const donationFormElement = document.querySelector(".donation-form");
  
  if (donationInfo && donationFormElement) {
    donationInfo.style.opacity = "0";
    donationInfo.style.transform = "translateY(20px)";
    donationInfo.style.transition = "opacity 0.5s ease, transform 0.5s ease";
    
    donationFormElement.style.opacity = "0";
    donationFormElement.style.transform = "translateY(20px)";
    donationFormElement.style.transition = "opacity 0.5s ease, transform 0.5s ease";
    
    setTimeout(() => {
      donationInfo.style.opacity = "1";
      donationInfo.style.transform = "translateY(0)";
    }, 300);
    
    setTimeout(() => {
      donationFormElement.style.opacity = "1";
      donationFormElement.style.transform = "translateY(0)";
    }, 500);
  }
});
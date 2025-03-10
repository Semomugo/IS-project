// JavaScript for forgot password page

document.addEventListener("DOMContentLoaded", () => {
  const forgotPasswordForm = document.getElementById("forgotPasswordForm")
  const submitBtn = document.getElementById("submitBtn")
  const loadingSpinner = document.getElementById("loadingSpinner")
  const successCheckmark = document.getElementById("successCheckmark")

  if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // Get form data
      const email = document.getElementById("email").value

      // Simple validation
      if (!email) {
        showMessage("Please enter your email address", "error")
        return
      }

      // Email format validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(email)) {
        showMessage("Please enter a valid email address", "error")
        return
      }

      // Show loading spinner
      submitBtn.disabled = true
      loadingSpinner.style.display = "inline-block"

      // Simulate form submission (in a real app, this would be an API call)
      simulatePasswordReset(email)
        .then((response) => {
          // Hide loading spinner
          loadingSpinner.style.display = "none"

          // Show success checkmark
          successCheckmark.style.display = "block"

          // Show success message
          showMessage("Password reset link has been sent to your email. Please check your inbox.", "success")

          // Reset form
          forgotPasswordForm.reset()

          // Re-enable button after a delay
          setTimeout(() => {
            submitBtn.disabled = false
            successCheckmark.style.display = "none"
          }, 3000)
        })
        .catch((error) => {
          // Hide loading spinner
          loadingSpinner.style.display = "none"
          submitBtn.disabled = false

          // Show error message
          showMessage(error.message, "error")
        })
    })
  }

  // Helper Functions
  function showMessage(message, type) {
    // Check if message element already exists
    let messageElement = document.querySelector(".contact-message")

    // If not, create a new one
    if (!messageElement) {
      messageElement = document.createElement("div")
      messageElement.className = "contact-message"

      // Insert before the form
      const form = document.getElementById("forgotPasswordForm")
      form.parentNode.insertBefore(messageElement, form)
    }

    // Set message content and style
    messageElement.textContent = message
    messageElement.className = `contact-message ${type}`

    // Auto-remove after 5 seconds
    setTimeout(() => {
      messageElement.remove()
    }, 5000)
  }

  // Simulate password reset request (mock API call)
  function simulatePasswordReset(email) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        // This is just a simulation - in a real app, this would be an API call
        if (Math.random() > 0.1) {
          // 90% success rate for demo
          resolve({ success: true })
        } else {
          reject({ message: "There was an error processing your request. Please try again later." })
        }
      }, 1500)
    })
  }
})
// JavaScript for contact page

document.addEventListener("DOMContentLoaded", () => {
  const contactForm = document.getElementById("contactForm")

  if (contactForm) {
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // Get form data
      const firstName = document.getElementById("firstName").value
      const lastName = document.getElementById("lastName").value
      const email = document.getElementById("email").value
      const company = document.getElementById("company").value
      const message = document.getElementById("message").value

      // Simple validation
      if (!firstName || !lastName || !email || !message) {
        showMessage("Please fill in all required fields", "error")
        return
      }

      // Simulate form submission (in a real app, this would be an API call)
      simulateFormSubmission(firstName, lastName, email, company, message)
        .then((response) => {
          showMessage("Your message has been sent successfully! We will get back to you soon.", "success")
          contactForm.reset()
        })
        .catch((error) => {
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
      const form = document.getElementById("contactForm")
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

  // Simulate form submission (mock API call)
  function simulateFormSubmission(firstName, lastName, email, company, message) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        // This is just a simulation - in a real app, this would be an API call
        if (Math.random() > 0.1) {
          // 90% success rate for demo
          resolve({ success: true })
        } else {
          reject({ message: "There was an error sending your message. Please try again later." })
        }
      }, 1500)
    })
  }

  // Add styles for contact messages
  const style = document.createElement("style")
  style.textContent = `
    .contact-message {
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 5px;
      text-align: center;
    }
    
    .contact-message.success {
      background-color: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }
    
    .contact-message.error {
      background-color: #ffebee;
      color: #c62828;
      border: 1px solid #ffcdd2;
    }
  `
  document.head.appendChild(style)
})


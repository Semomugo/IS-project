document.addEventListener("DOMContentLoaded", () => {
  const contactForm = document.getElementById("contactForm")

  if (contactForm) {
    // Add form submission handling
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // Get form values
      const firstName = document.getElementById("firstName").value
      const lastName = document.getElementById("lastName").value
      const email = document.getElementById("email").value
      const company = document.getElementById("company").value
      const message = document.getElementById("message").value

      // Simple validation
      if (!firstName || !lastName || !email || !message) {
        alert("Please fill in all required fields")
        return
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(email)) {
        alert("Please enter a valid email address")
        return
      }

      // If validation passes, you would typically send the data to a server
      // For now, we'll just show a success message
      alert(`Thank you, ${firstName}! Your message has been sent successfully. We'll get back to you soon.`)
      contactForm.reset()
    })

    // Add input focus effects
    const formInputs = contactForm.querySelectorAll("input, textarea")
    formInputs.forEach((input) => {
      // Add floating effect on focus
      input.addEventListener("focus", function () {
        this.parentElement.classList.add("focused")
      })

      // Remove floating effect if field is empty
      input.addEventListener("blur", function () {
        if (!this.value) {
          this.parentElement.classList.remove("focused")
        }
      })

      // Check if input already has value on page load
      if (input.value) {
        input.parentElement.classList.add("focused")
      }
    })
  }

  // Add animation to contact info items
  const infoItems = document.querySelectorAll(".info-item")
  if (infoItems.length > 0) {
    infoItems.forEach((item, index) => {
      item.style.opacity = "0"
      item.style.transform = "translateY(20px)"
      item.style.transition = "opacity 0.5s ease, transform 0.5s ease"

      setTimeout(
        () => {
          item.style.opacity = "1"
          item.style.transform = "translateY(0)"
        },
        300 + index * 100,
      )
    })
  }
})


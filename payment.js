document.addEventListener("DOMContentLoaded", () => {
  const donationForm = document.getElementById("donationForm")
  const formInputs = donationForm.querySelectorAll("input")

  // Add floating label effect
  formInputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.classList.add("focused")
    })

    input.addEventListener("blur", function () {
      if (this.value === "") {
        this.parentElement.classList.remove("focused")
      }
    })

    // Check on load if the input has a value
    if (input.value !== "") {
      input.parentElement.classList.add("focused")
    }
  })

  // Form submission
  donationForm.addEventListener("submit", (e) => {
    e.preventDefault()

    // Basic form validation
    let isValid = true
    formInputs.forEach((input) => {
      if (input.value.trim() === "") {
        isValid = false
        input.classList.add("error")
      } else {
        input.classList.remove("error")
      }
    })

    if (isValid) {
      // Here you would typically send the form data to a server
      alert("Thank you for your donation!")
      donationForm.reset()
    } else {
      alert("Please fill in all fields.")
    }
  })

  // Animate recent donations on scroll
  const recentDonations = document.querySelector(".recent-donations")
  const tableRows = recentDonations.querySelectorAll("tbody tr")

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          tableRows.forEach((row, index) => {
            setTimeout(() => {
              row.style.opacity = "1"
              row.style.transform = "translateY(0)"
            }, index * 100)
          })
          observer.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.5 },
  )

  observer.observe(recentDonations)

  // Initialize table rows as hidden
  tableRows.forEach((row) => {
    row.style.opacity = "0"
    row.style.transform = "translateY(20px)"
    row.style.transition = "opacity 0.5s ease, transform 0.5s ease"
  })
})


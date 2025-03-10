document.addEventListener("DOMContentLoaded", () => {
  // Animate impact numbers
  const impactItems = document.querySelectorAll(".impact-item h3")

  const animateValue = (obj, start, end, duration) => {
    let startTimestamp = null
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp
      const progress = Math.min((timestamp - startTimestamp) / duration, 1)
      obj.innerHTML = Math.floor(progress * (end - start) + start)
      if (progress < 1) {
        window.requestAnimationFrame(step)
      }
    }
    window.requestAnimationFrame(step)
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const target = entry.target
          const endValue = Number.parseInt(target.getAttribute("data-value"))
          animateValue(target, 0, endValue, 2000)
          observer.unobserve(target)
        }
      })
    },
    { threshold: 0.5 },
  )

  impactItems.forEach((item) => {
    const value = item.textContent
    item.textContent = "0"
    item.setAttribute("data-value", value)
    observer.observe(item)
  })

  // Smooth scroll for navigation links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      })
    })
  })

  // Add parallax effect to hero section
  const hero = document.querySelector(".hero")
  window.addEventListener("scroll", () => {
    const scrollPosition = window.pageYOffset
    hero.style.backgroundPositionY = `${scrollPosition * 0.5}px`
  })

  // Add animation to project cards
  const projectCards = document.querySelectorAll(".project-card")
  projectCards.forEach((card, index) => {
    card.style.opacity = "0"
    card.style.transform = "translateY(20px)"
    card.style.transition = "opacity 0.5s ease, transform 0.5s ease"

    setTimeout(
      () => {
        card.style.opacity = "1"
        card.style.transform = "translateY(0)"
      },
      200 * (index + 1),
    )
  })
})


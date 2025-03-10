document.addEventListener("DOMContentLoaded", () => {
    // Animate impact numbers in donation stats
    const statNumbers = document.querySelectorAll(".stat h5");
    
    const animateValue = (obj, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            
            // Extract the numeric part and any suffix (like +)
            const text = obj.textContent;
            const hasSuffix = text.includes("+");
            const suffix = hasSuffix ? "+" : "";
            
            // Set the new value with the suffix if it exists
            obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString() + suffix;
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };
    
    // Use Intersection Observer to trigger animations when elements come into view
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    // Clean the text to get just the number
                    const value = target.textContent.replace(/,/g, "").replace(/\+/g, "");
                    const endValue = parseInt(value, 10);
                    animateValue(target, 0, endValue, 2000);
                    observer.unobserve(target);
                }
            });
        },
        { threshold: 0.5 }
    );
    
    // Observe all stat numbers
    statNumbers.forEach((item) => {
        observer.observe(item);
    });
    
    // Add animation to project cards
    const projectCards = document.querySelectorAll(".project-card");
    projectCards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "opacity 0.5s ease, transform 0.5s ease";
        
        setTimeout(
            () => {
                card.style.opacity = "1";
                card.style.transform = "translateY(0)";
            },
            200 * (index + 1)
        );
    });
    
    // Add animation to team members
    const teamMembers = document.querySelectorAll(".team-member");
    teamMembers.forEach((member, index) => {
        member.style.opacity = "0";
        member.style.transform = "translateY(20px)";
        member.style.transition = "opacity 0.5s ease, transform 0.5s ease";
        
        setTimeout(
            () => {
                member.style.opacity = "1";
                member.style.transform = "translateY(0)";
            },
            200 * (index + 1)
        );
    });
    
    // Add parallax effect to hero section
    const hero = document.querySelector(".about-hero");
    window.addEventListener("scroll", () => {
        const scrollPosition = window.pageYOffset;
        hero.style.backgroundPositionY = `${scrollPosition * 0.5}px`;
    });
    
    // Add smooth scrolling for navigation
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute("href")).scrollIntoView({
                behavior: "smooth",
            });
        });
    });
});
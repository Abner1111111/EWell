// main.js - Landing page interactions

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70, // Adjust for navbar height
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form submission handling
    const ctaForm = document.querySelector('.cta-form');
    if (ctaForm) {
        ctaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const formValues = {};
            for (let [key, value] of formData.entries()) {
                formValues[key] = value;
            }
            
            // Here you would typically send the data to your server
            console.log('Form submitted with values:', formValues);
            
            // Show success message
            alert('Thank you for your interest! We will contact you soon.');
            this.reset();
        });
    }
    
    // Add active class to feature cards when scrolled into view
    const featureCards = document.querySelectorAll('.feature-card');
    
    function checkScroll() {
        featureCards.forEach(card => {
            const cardPosition = card.getBoundingClientRect();
            
            // If card is in viewport
            if (cardPosition.top < window.innerHeight - 100 && cardPosition.bottom > 0) {
                card.classList.add('active');
            }
        });
    }
    
    // Check on scroll and initial load
    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Check on page load
    
    // Simple testimonial slider functionality
    let currentSlide = 0;
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    
    if (testimonialCards.length > 0 && window.innerWidth > 768) {
        // Only setup auto-scroll for desktop
        setInterval(() => {
            currentSlide = (currentSlide + 1) % testimonialCards.length;
            const scrollAmount = currentSlide * (testimonialCards[0].offsetWidth + 32); // Card width + gap
            document.querySelector('.testimonial-slider').scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }, 5000); // Change slide every 5 seconds
    }
}); 
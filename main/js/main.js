// main.js - Landing page interactions

document.addEventListener('DOMContentLoaded', function() {
    // Form submission handling
    const ctaForm = document.querySelector('.feedback-form');
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
            alert('Thank you for your feedback! It helps us improve.');
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
        
        // Check if ranks section is in view to animate bars
        const ranksSection = document.querySelector('.ranks');
        if (ranksSection) {
            const ranksSectionPosition = ranksSection.getBoundingClientRect();
            if (ranksSectionPosition.top < window.innerHeight - 200 && ranksSectionPosition.bottom > 0) {
                ranksSection.classList.add('in-view');
            }
        }
    }
    
    // Initialize bar chart on load
    function initBarChart() {
        const bars = document.querySelectorAll('.bar');
        bars.forEach(bar => {
            const value = bar.getAttribute('data-value');
            const barFill = bar.querySelector('.bar-fill');
            
            // Set initial width to 0 (animation will handle the rest)
            barFill.style.width = '0%';
        });
    }
    
    // Check on scroll and initial load
    window.addEventListener('scroll', checkScroll);
    initBarChart();
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
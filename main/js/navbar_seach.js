// navbar_search.js

/**
 * Toggles the search bar visibility and animation
 */
function toggleSearch() {
    const searchBar = document.getElementById('searchBar');
    const searchInput = document.getElementById('searchInput');
    
    searchBar.classList.toggle('active');
    
    if (searchBar.classList.contains('active')) {
        // Focus the input field after animation starts
        setTimeout(() => {
            searchInput.focus();
        }, 300);
    }
}

/**
 * Toggles the mobile menu
 */
function toggleMenu() {
    const navLinks = document.getElementById('navLinks');
    const menuToggle = document.querySelector('.menu-toggle');
    navLinks.classList.toggle('active');
    
    // Change menu icon based on state
    if (navLinks.classList.contains('active')) {
        menuToggle.innerHTML = '<i class="fas fa-times"></i>';
    } else {
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    }
}

/**
 * Handles smooth scrolling to section when clicking on navigation links
 */
function smoothScrollToSection(e) {
    // Only handle links pointing to sections on the same page
    const targetId = this.getAttribute('href');
    
    if (targetId && targetId !== '#' && targetId.startsWith('#')) {
        e.preventDefault();
        
        const sectionId = targetId.substring(1);
        const targetElement = document.getElementById(sectionId);
        
        if (targetElement) {
            // Close mobile menu if open
            const navLinks = document.getElementById('navLinks');
            if (navLinks && navLinks.classList.contains('active')) {
                toggleMenu();
            }
            
            // Scroll to the target section
            window.scrollTo({
                top: targetElement.offsetTop - 70, // Adjust for navbar height
                behavior: 'smooth'
            });
            
            // Update active class
            const navLinkElements = document.querySelectorAll('.nav-links a');
            navLinkElements.forEach(link => {
                link.classList.remove('active');
            });
            this.classList.add('active');
        }
    }
}

// Add event listeners when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Close search when clicking outside
    document.addEventListener('click', function(event) {
        const searchBar = document.getElementById('searchBar');
        const searchIcon = document.querySelector('.search-icon');
        
        // If search is active and click is outside search bar
        if (searchBar && searchBar.classList.contains('active') && 
            !searchBar.contains(event.target) && 
            event.target !== searchIcon) {
            searchBar.classList.remove('active');
        }
    });

    // Handle navigation links
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        link.addEventListener('click', smoothScrollToSection);
    });
    
    // Set up initial active states based on scroll position
    setActiveMenuItem();
    
    // Update active menu item on scroll
    window.addEventListener('scroll', debounce(setActiveMenuItem, 100));
    
    // Add subtle shadow to navbar on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            if (window.scrollY > 10) {
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.08)';
            }
        }
    });
    
    // Initialize bar chart animations if on index page with chart
    const chartContainer = document.querySelector('.bar-chart');
    if (chartContainer) {
        initBarChart();
    }
});

/**
 * Debounce function to limit how often a function is called
 */
function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

/**
 * Sets the active menu item based on the current scroll position
 */
function setActiveMenuItem() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-links a');
    
    // Get current scroll position
    let scrollY = window.pageYOffset;
    let activeSection = '';
    
    // Find the active section based on scroll position
    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100; // Adjust for navbar height
        const sectionId = section.getAttribute('id');
        
        if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
            activeSection = sectionId;
        }
    });
    
    // Update active class on nav links
    navLinks.forEach(link => {
        link.classList.remove('active');
        
        const href = link.getAttribute('href');
        if (href === '#' && scrollY < 300) {
            // Home link is active at the top of the page
            link.classList.add('active');
        } else if (href === `#${activeSection}`) {
            // Section link matches current section
            link.classList.add('active');
        }
    });
}

/**
 * Initialize bar chart animation
 */
function initBarChart() {
    const bars = document.querySelectorAll('.bar');
    
    bars.forEach(bar => {
        const value = bar.getAttribute('data-value');
        const barFill = bar.querySelector('.bar-fill');
        
        // Set initial width to 0 (animation will handle the rest)
        if (barFill) {
            barFill.style.width = '0%';
            
            // Set up intersection observer to animate when visible
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            setTimeout(() => {
                                barFill.style.width = `${value}%`;
                            }, 300);
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.2 }
            );
            
            observer.observe(bar);
        }
    });
}
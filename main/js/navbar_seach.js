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
    navLinks.classList.toggle('active');
}

// Add event listeners when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Close search when clicking outside
    document.addEventListener('click', function(event) {
        const searchBar = document.getElementById('searchBar');
        const searchIcon = document.querySelector('.search-icon');
        
        // If search is active and click is outside search bar
        if (searchBar.classList.contains('active') && 
            !searchBar.contains(event.target) && 
            event.target !== searchIcon) {
            searchBar.classList.remove('active');
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Update current date
    const currentDate = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('current-date').textContent = currentDate.toLocaleDateString('en-US', options);

    // Add click event to notification icon
    const notificationIcon = document.querySelector('.user-menu i.fa-bell');
    notificationIcon.addEventListener('click', function() {
        // Add your notification logic here
        alert('No new notifications');
    });

    // Add hover effect to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 6px 15px rgba(0, 0, 0, 0.1)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.1)';
        });
    });

    // Add click event to user profile
    const userProfile = document.querySelector('.user-profile');
    userProfile.addEventListener('click', function() {
        // Add your profile menu logic here
        alert('Profile menu clicked');
    });

    // Add search functionality
    const searchInput = document.querySelector('.search-bar input');
    searchInput.addEventListener('input', function(e) {
        // Add your search logic here
        console.log('Searching for:', e.target.value);
    });

    // Add navigation click events
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // Remove active class from all links
            navLinks.forEach(l => l.parentElement.classList.remove('active'));
            // Add active class to clicked link
            this.parentElement.classList.add('active');
        });
    });

    // Simulate progress bar animations
    const progressBars = document.querySelectorAll('.progress');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 200);
    });

    // Settings dropdown functionality
    const settingsIcon = document.querySelector('.settings-icon');
    const settingsDropdown = document.querySelector('.settings-dropdown');

    settingsIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        this.classList.toggle('active');
        settingsDropdown.classList.toggle('active');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!settingsDropdown.contains(e.target) && !settingsIcon.contains(e.target)) {
            settingsIcon.classList.remove('active');
            settingsDropdown.classList.remove('active');
        }
    });

    // Prevent dropdown from closing when clicking inside
    settingsDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}); 
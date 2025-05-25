document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'btn btn-primary d-md-none position-fixed';
    toggleBtn.style.cssText = 'top: 1rem; left: 1rem; z-index: 1001;';
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    document.body.appendChild(toggleBtn);

    toggleBtn.addEventListener('click', function() {
        document.querySelector('.admin-sidebar').classList.toggle('show');
    });
});
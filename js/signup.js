document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const nextBtn = document.querySelector('.next-btn');
    const prevBtn = document.querySelector('.prev-btn');
    const submitBtn = document.querySelector('.submit-btn');
    let currentStep = 1;

    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Update progress tracker
    function updateProgress() {
        progressSteps.forEach((step, index) => {
            if (index + 1 < currentStep) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (index + 1 === currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }

    // Show/hide navigation buttons
    function updateButtons() {
        if (currentStep === 1) {
            prevBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'flex';
        }

        if (currentStep === steps.length) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'flex';
        } else {
            nextBtn.style.display = 'flex';
            submitBtn.style.display = 'none';
        }
    }

    // Validate current step
    function validateStep() {
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });

        // Special validation for password confirmation
        if (currentStep === 2) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('error');
                isValid = false;
                // Show error message
                let errorMsg = confirmPassword.parentNode.querySelector('.error-text');
                if (!errorMsg) {
                    errorMsg = document.createElement('span');
                    errorMsg.className = 'error-text';
                    errorMsg.style.color = '#f44336';
                    errorMsg.style.fontSize = '12px';
                    confirmPassword.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = 'Passwords do not match';
            } else {
                confirmPassword.classList.remove('error');
                const errorMsg = confirmPassword.parentNode.querySelector('.error-text');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        }

        // Special validation for terms checkbox in step 4
        if (currentStep === 4) {
            const termsCheckbox = document.querySelector('input[name="terms"]');
            if (!termsCheckbox.checked) {
                isValid = false;
                termsCheckbox.classList.add('error');
            } else {
                termsCheckbox.classList.remove('error');
            }
        }

        return isValid;
    }

    // Next button click handler
    nextBtn.addEventListener('click', () => {
        if (validateStep()) {
            steps[currentStep - 1].classList.remove('active');
            currentStep++;
            steps[currentStep - 1].classList.add('active');
            updateProgress();
            updateButtons();
        }
    });

    // Previous button click handler
    prevBtn.addEventListener('click', () => {
        steps[currentStep - 1].classList.remove('active');
        currentStep--;
        steps[currentStep - 1].classList.add('active');
        updateProgress();
        updateButtons();
    });

    // Form submission handler - REMOVED THE PREVENTION AND JAVASCRIPT OVERRIDE
    // The form will now submit normally to PHP for processing
    form.addEventListener('submit', (e) => {
        // Only validate the current step before allowing submission
        if (!validateStep()) {
            e.preventDefault();
            return false;
        }
        // If validation passes, let the form submit normally to PHP
    });

    // Initialize
    updateProgress();
    updateButtons();
});
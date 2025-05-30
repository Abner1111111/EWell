document.addEventListener('DOMContentLoaded', function() {
    // Handle search input
    const searchInput = document.querySelector('input[placeholder="Search articles..."]');
    const categorySelect = document.querySelector('select[name="category"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const sortSelect = document.querySelector('select[name="sort"]');

    function updateFilters() {
        const searchParams = new URLSearchParams(window.location.search);
        if (searchInput) searchParams.set('search', searchInput.value);
        if (categorySelect) searchParams.set('category', categorySelect.value);
        if (statusSelect) searchParams.set('status', statusSelect.value);
        if (sortSelect) searchParams.set('sort', sortSelect.value === 'Newest First' ? 'newest' : 'oldest');
        window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
    }

    // Add event listeners for filter changes
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                updateFilters();
            }
        });
    }

    // Add event listeners only to elements that exist
    [categorySelect, statusSelect, sortSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', updateFilters);
        }
    });

    // Handle article actions
    document.querySelectorAll('.btn-group').forEach(group => {
        const articleCard = group.closest('.article-card');
        if (!articleCard) return;
        
        const articleId = articleCard.dataset.articleId;
        if (!articleId) return;

        // Deactivate button
        const deactivateBtn = group.querySelector('.btn-warning');
        if (deactivateBtn) {
            deactivateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to deactivate this article?')) {
                    fetch('process_article.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=deactivate&id=${articleId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error deactivating article');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deactivating article');
                    });
                }
            });
        }

        // Delete button
        const deleteBtn = group.querySelector('.btn-danger');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this article? This action cannot be undone.')) {
                    fetch('process_article.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete&id=${articleId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            articleCard.remove();
                        } else {
                            alert('Error deleting article');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting article');
                    });
                }
            });
        }

        // Edit button
        const editBtn = group.querySelector('.btn-primary');
        if (editBtn) {
            editBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = `edit_article.php?id=${articleId}`;
            });
        }
    });

    // Handle success/error messages
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-danger');

    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }, 3000);
    }

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.opacity = '0';
            setTimeout(() => errorMessage.remove(), 300);
        }, 3000);
    }

    // Form validation for new article
    const newArticleForm = document.getElementById('newArticleForm');
    if (newArticleForm) {
        // Prevent form controls from triggering URL changes
        newArticleForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        newArticleForm.addEventListener('submit', function(e) {
            const title = this.querySelector('[name="title"]')?.value.trim() || '';
            const content = this.querySelector('[name="content"]')?.value.trim() || '';
            const summary = this.querySelector('[name="summary"]')?.value.trim() || '';

            if (title.length < 5) {
                e.preventDefault();
                alert('Title must be at least 5 characters long');
                return;
            }

            if (content.length < 50) {
                e.preventDefault();
                alert('Content must be at least 50 characters long');
                return;
            }

            if (summary.length < 10) {
                e.preventDefault();
                alert('Summary must be at least 10 characters long');
                return;
            }
        });
    }
}); 
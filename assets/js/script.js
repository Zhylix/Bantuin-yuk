// Bantuin Yuk - Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initAnimations();
    initForms();
    initNotifications();
    initMap();
    initTags();
    initModals();
    initMobileMenu();
});

// Animation Controller
function initAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements with animation classes
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.parallax');
        if (parallax) {
            parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
}

// Form Handling
function initForms() {
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showNotification('Harap periksa kembali form Anda.', 'error');
            }
        });

        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    });

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea[data-autoresize]');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
}

// Form Validation Functions
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name') || field.getAttribute('type');
    let isValid = true;
    let message = '';

    // Clear previous errors
    clearFieldError(field);

    // Required validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        message = 'Field ini wajib diisi.';
    }

    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            message = 'Format email tidak valid.';
        }
    }

    // Password validation
    if (field.type === 'password' && value) {
        if (value.length < 6) {
            isValid = false;
            message = 'Password minimal 6 karakter.';
        }
    }

    // Phone validation
    if (field.type === 'tel' && value) {
        const phoneRegex = /^[0-9+\-\s()]{10,}$/;
        if (!phoneRegex.test(value)) {
            isValid = false;
            message = 'Format nomor telepon tidak valid.';
        }
    }

    if (!isValid) {
        showFieldError(field, message);
    }

    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('border-red-500', 'bg-red-50');
    
    let errorElement = field.parentNode.querySelector('.field-error');
    if (!errorElement) {
        errorElement = document.createElement('p');
        errorElement.className = 'field-error text-red-500 text-xs mt-1';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('border-red-500', 'bg-red-50');
    
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// Notification System
function initNotifications() {
    // Create notification container if it doesn't exist
    if (!document.getElementById('notification-container')) {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2 max-w-sm';
        document.body.appendChild(container);
    }
}

function showNotification(message, type = 'info', duration = 5000) {
    const container = document.getElementById('notification-container');
    const notification = document.createElement('div');
    
    const typeClasses = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };

    notification.className = `p-4 rounded-lg shadow-lg transform transition-all duration-300 ${typeClasses[type]} animate-slide-up`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-${getNotificationIcon(type)} mr-2"></i>
                <span>${message}</span>
            </div>
            <button class="ml-4 text-white hover:text-gray-200 transition-colors" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    container.appendChild(notification);

    // Auto remove after duration
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }
    }, duration);
}

function getNotificationIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

// Map Functionality
function initMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    // Initialize map (using Leaflet as example)
    const map = L.map('map').setView([-6.2, 106.8], 10); // Default to Jakarta

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add markers from data
    const requests = JSON.parse(mapElement.dataset.requests || '[]');
    requests.forEach(request => {
        const marker = L.marker([request.lat, request.lng]).addTo(map);
        marker.bindPopup(`
            <div class="p-2">
                <h3 class="font-bold">${request.title}</h3>
                <p class="text-sm">${request.description}</p>
                <div class="mt-2">
                    ${request.tags.map(tag => `<span class="tag bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">${tag}</span>`).join('')}
                </div>
                <a href="/request/view.php?id=${request.id}" class="block mt-2 text-blue-600 hover:text-blue-800 text-sm">Lihat Detail</a>
            </div>
        `);
    });

    // Fit bounds to show all markers
    if (requests.length > 0) {
        const group = new L.featureGroup(requests.map(r => L.marker([r.lat, r.lng])));
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Tag Management
function initTags() {
    const tagInputs = document.querySelectorAll('.tag-input');
    
    tagInputs.forEach(input => {
        const container = input.parentNode;
        const tagsContainer = container.querySelector('.tags-container');
        const hiddenInput = container.querySelector('input[type="hidden"]');
        
        let selectedTags = hiddenInput.value ? hiddenInput.value.split(',') : [];

        // Initialize tags display
        updateTagsDisplay();

        // Tag selection
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('tag-option')) {
                const tagId = e.target.dataset.tagId;
                const tagName = e.target.dataset.tagName;
                
                if (!selectedTags.includes(tagId)) {
                    selectedTags.push(tagId);
                    updateTagsDisplay();
                    updateHiddenInput();
                }
                
                e.preventDefault();
            }
            
            if (e.target.classList.contains('remove-tag')) {
                const tagId = e.target.parentNode.dataset.tagId;
                selectedTags = selectedTags.filter(id => id !== tagId);
                updateTagsDisplay();
                updateHiddenInput();
            }
        });

        function updateTagsDisplay() {
            tagsContainer.innerHTML = '';
            selectedTags.forEach(tagId => {
                const tagOption = container.querySelector(`.tag-option[data-tag-id="${tagId}"]`);
                if (tagOption) {
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2 mb-2';
                    tag.dataset.tagId = tagId;
                    tag.innerHTML = `
                        ${tagOption.dataset.tagName}
                        <button type="button" class="remove-tag ml-1 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    `;
                    tagsContainer.appendChild(tag);
                }
            });
        }

        function updateHiddenInput() {
            hiddenInput.value = selectedTags.join(',');
        }
    });
}

// Modal System
function initModals() {
    // Open modal
    document.querySelectorAll('[data-modal-toggle]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.dataset.modalToggle;
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }
        });
    });

    // Close modal
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.dataset.modalHide;
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });

    // Close modal on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', function(e) {
            if (e.target === this) {
                const modal = this.closest('.modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        });
    });
}

// Mobile Menu
function initMobileMenu() {
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileMenu && !mobileMenu.contains(e.target) && !menuButton.contains(e.target)) {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('flex');
        }
    });
}

// Search and Filter
function initSearch() {
    const searchInput = document.getElementById('search-input');
    const filterButtons = document.querySelectorAll('.filter-button');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            performSearch(this.value);
        }, 300));
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            performFilter(filter);
        });
    });
}

function performSearch(query) {
    const items = document.querySelectorAll('.searchable-item');
    const searchTerm = query.toLowerCase();
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = '';
            item.classList.add('animate-fade-in');
        } else {
            item.style.display = 'none';
        }
    });
}

function performFilter(filter) {
    const items = document.querySelectorAll('.filterable-item');
    
    items.forEach(item => {
        if (filter === 'all' || item.dataset.category === filter) {
            item.style.display = '';
            item.classList.add('animate-fade-in');
        } else {
            item.style.display = 'none';
        }
    });
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// API Functions
async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        }
    };

    if (data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(endpoint, options);
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Terjadi kesalahan');
        }
        
        return result;
    } catch (error) {
        showNotification(error.message, 'error');
        throw error;
    }
}

// Export functions for global use
window.showNotification = showNotification;
window.formatDate = formatDate;
window.formatNumber = formatNumber;
window.apiCall = apiCall;
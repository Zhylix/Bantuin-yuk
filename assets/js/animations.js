// Bantuin Yuk - Animations JavaScript File

// Bantuin Yuk - Animations JavaScript File

class BantuinYukAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.initScrollAnimations();
        this.initCounterAnimations();
        this.initParallaxEffects();
        this.initHoverEffects();
        this.initLoadingAnimations();
    }

    // Scroll Animations
    initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateElement(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }

    animateElement(element) {
        const animationType = element.dataset.animation || 'fadeInUp';
        
        switch (animationType) {
            case 'fadeInUp':
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
                break;
            case 'fadeInLeft':
                element.style.opacity = '1';
                element.style.transform = 'translateX(0)';
                break;
            case 'fadeInRight':
                element.style.opacity = '1';
                element.style.transform = 'translateX(0)';
                break;
            case 'zoomIn':
                element.style.opacity = '1';
                element.style.transform = 'scale(1)';
                break;
            case 'bounceIn':
                element.style.opacity = '1';
                element.style.transform = 'scale(1)';
                element.classList.add('animate-bounce');
                break;
        }

        element.style.transition = 'all 0.6s ease-out';
    }

    // Counter Animations
    initCounterAnimations() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = +counter.dataset.target;
            const duration = +counter.dataset.duration || 2000;
            const increment = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    if (current > target) current = target;
                    counter.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                }
            };

            // Start counting when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });

            observer.observe(counter);
        });
    }

    // Parallax Effects
    initParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.parallax');
        
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
        });
    }

    // Hover Effects
    initHoverEffects() {
        // Card hover effects
        const cards = document.querySelectorAll('.card-hover');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Button hover effects
        const buttons = document.querySelectorAll('.btn-animate');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', (e) => {
                const x = e.pageX - button.offsetLeft;
                const y = e.pageY - button.offsetTop;
                
                button.style.setProperty('--x', x + 'px');
                button.style.setProperty('--y', y + 'px');
            });
        });
    }

    // Loading Animations
    initLoadingAnimations() {
        // Page load animation
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
            
            // Animate hero section
            const heroElements = document.querySelectorAll('.hero-animate');
            heroElements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('animate-in');
                }, index * 200);
            });
        });

        // Loading states for forms
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', () => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                    submitBtn.disabled = true;
                }
            });
        });
    }

    // Utility Methods
    fadeIn(element, duration = 600) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const opacity = Math.min(progress / duration, 1);
            
            element.style.opacity = opacity.toString();
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    fadeOut(element, duration = 600) {
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const opacity = 1 - Math.min(progress / duration, 1);
            
            element.style.opacity = opacity.toString();
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideDown(element, duration = 600) {
        element.style.display = 'block';
        const height = element.scrollHeight;
        element.style.height = '0px';
        element.style.overflow = 'hidden';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const currentHeight = (progress / duration) * height;
            
            element.style.height = Math.min(currentHeight, height) + 'px';
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            } else {
                element.style.height = 'auto';
                element.style.overflow = 'visible';
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideUp(element, duration = 600) {
        const height = element.scrollHeight;
        element.style.height = height + 'px';
        element.style.overflow = 'hidden';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const currentHeight = height - (progress / duration) * height;
            
            element.style.height = Math.max(currentHeight, 0) + 'px';
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
                element.style.height = 'auto';
                element.style.overflow = 'visible';
            }
        };
        
        requestAnimationFrame(animate);
    }

    // Confetti animation for success events
    showConfetti() {
        const confettiContainer = document.createElement('div');
        confettiContainer.className = 'confetti-container';
        confettiContainer.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        `;
        document.body.appendChild(confettiContainer);

        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.cssText = `
                position: absolute;
                width: 10px;
                height: 10px;
                background: ${this.getRandomColor()};
                top: -10px;
                left: ${Math.random() * 100}%;
                border-radius: 2px;
                animation: confetti-fall ${2 + Math.random() * 3}s linear forwards;
            `;

            confettiContainer.appendChild(confetti);
        }

        // Remove confetti after animation
        setTimeout(() => {
            confettiContainer.remove();
        }, 5000);
    }

    getRandomColor() {
        const colors = [
            '#EF4444', '#F59E0B', '#10B981', '#3B82F6', 
            '#8B5CF6', '#EC4899', '#6B7280', '#84CC16'
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    // Pulse animation for important elements
    pulseElement(element, times = 3) {
        let count = 0;
        const pulse = () => {
            element.classList.add('animate-pulse');
            setTimeout(() => {
                element.classList.remove('animate-pulse');
                count++;
                if (count < times) {
                    setTimeout(pulse, 500);
                }
            }, 1000);
        };
        pulse();
    }

    // Shake animation for errors
    shakeElement(element) {
        element.classList.add('animate-shake');
        setTimeout(() => {
            element.classList.remove('animate-shake');
        }, 500);
    }

    // Typewriter effect
    typeWriter(element, text, speed = 50) {
        let i = 0;
        element.innerHTML = '';
        
        const type = () => {
            if (i < text.length) {
                element.innerHTML += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        };
        
        type();
    }

    // Progress bar animation
    animateProgressBar(progressBar, targetWidth, duration = 1000) {
        let startWidth = 0;
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const currentWidth = startWidth + (targetWidth - startWidth) * progress;
            progressBar.style.width = currentWidth + '%';
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.bantuinYukAnimations = new BantuinYukAnimations();
});

// Additional CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes confetti-fall {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
        }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .animate-glow {
        animation: glow 2s ease-in-out infinite;
    }

    .hero-animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }

    .hero-animate.animate-in {
        opacity: 1;
        transform: translateY(0);
    }

    .stagger-animate > * {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease-out;
    }

    .stagger-animate.animate-in > *:nth-child(1) { transition-delay: 0.1s; }
    .stagger-animate.animate-in > *:nth-child(2) { transition-delay: 0.2s; }
    .stagger-animate.animate-in > *:nth-child(3) { transition-delay: 0.3s; }
    .stagger-animate.animate-in > *:nth-child(4) { transition-delay: 0.4s; }
    .stagger-animate.animate-in > *:nth-child(5) { transition-delay: 0.5s; }

    .stagger-animate.animate-in > * {
        opacity: 1;
        transform: translateY(0);
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
`;
document.head.appendChild(style);

class BantuinYukAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.initScrollAnimations();
        this.initCounterAnimations();
        this.initParallaxEffects();
        this.initHoverEffects();
        this.initLoadingAnimations();
    }

    // Scroll Animations
    initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateElement(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }

    animateElement(element) {
        const animationType = element.dataset.animation || 'fadeInUp';
        
        switch (animationType) {
            case 'fadeInUp':
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
                break;
            case 'fadeInLeft':
                element.style.opacity = '1';
                element.style.transform = 'translateX(0)';
                break;
            case 'fadeInRight':
                element.style.opacity = '1';
                element.style.transform = 'translateX(0)';
                break;
            case 'zoomIn':
                element.style.opacity = '1';
                element.style.transform = 'scale(1)';
                break;
            case 'bounceIn':
                element.style.opacity = '1';
                element.style.transform = 'scale(1)';
                element.classList.add('animate-bounce');
                break;
        }

        element.style.transition = 'all 0.6s ease-out';
    }

    // Counter Animations
    initCounterAnimations() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = +counter.dataset.target;
            const duration = +counter.dataset.duration || 2000;
            const increment = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    if (current > target) current = target;
                    counter.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                }
            };

            // Start counting when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });

            observer.observe(counter);
        });
    }

    // Parallax Effects
    initParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.parallax');
        
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
        });
    }

    // Hover Effects
    initHoverEffects() {
        // Card hover effects
        const cards = document.querySelectorAll('.card-hover');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Button hover effects
        const buttons = document.querySelectorAll('.btn-animate');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', (e) => {
                const x = e.pageX - button.offsetLeft;
                const y = e.pageY - button.offsetTop;
                
                button.style.setProperty('--x', x + 'px');
                button.style.setProperty('--y', y + 'px');
            });
        });
    }

    // Loading Animations
    initLoadingAnimations() {
        // Page load animation
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
            
            // Animate hero section
            const heroElements = document.querySelectorAll('.hero-animate');
            heroElements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('animate-in');
                }, index * 200);
            });
        });

        // Loading states for forms
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', () => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                    submitBtn.disabled = true;
                }
            });
        });
    }

    // Utility Methods
    fadeIn(element, duration = 600) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const opacity = Math.min(progress / duration, 1);
            
            element.style.opacity = opacity.toString();
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    fadeOut(element, duration = 600) {
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const opacity = 1 - Math.min(progress / duration, 1);
            
            element.style.opacity = opacity.toString();
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideDown(element, duration = 600) {
        element.style.display = 'block';
        const height = element.scrollHeight;
        element.style.height = '0px';
        element.style.overflow = 'hidden';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const currentHeight = (progress / duration) * height;
            
            element.style.height = Math.min(currentHeight, height) + 'px';
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            } else {
                element.style.height = 'auto';
                element.style.overflow = 'visible';
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideUp(element, duration = 600) {
        const height = element.scrollHeight;
        element.style.height = height + 'px';
        element.style.overflow = 'hidden';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const currentHeight = height - (progress / duration) * height;
            
            element.style.height = Math.max(currentHeight, 0) + 'px';
            
            if (progress < duration) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
                element.style.height = 'auto';
                element.style.overflow = 'visible';
            }
        };
        
        requestAnimationFrame(animate);
    }

    // Confetti animation for success events
    showConfetti() {
        const confettiContainer = document.createElement('div');
        confettiContainer.className = 'confetti-container';
        confettiContainer.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        `;
        document.body.appendChild(confettiContainer);

        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.cssText = `
                position: absolute;
                width: 10px;
                height: 10px;
                background: ${this.getRandomColor()};
                top: -10px;
                left: ${Math.random() * 100}%;
                border-radius: 2px;
                animation: confetti-fall ${2 + Math.random() * 3}s linear forwards;
            `;

            confettiContainer.appendChild(confetti);
        }

        // Remove confetti after animation
        setTimeout(() => {
            confettiContainer.remove();
        }, 5000);
    }

    getRandomColor() {
        const colors = [
            '#EF4444', '#F59E0B', '#10B981', '#3B82F6', 
            '#8B5CF6', '#EC4899', '#6B7280', '#84CC16'
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    // Pulse animation for important elements
    pulseElement(element, times = 3) {
        let count = 0;
        const pulse = () => {
            element.classList.add('animate-pulse');
            setTimeout(() => {
                element.classList.remove('animate-pulse');
                count++;
                if (count < times) {
                    setTimeout(pulse, 500);
                }
            }, 1000);
        };
        pulse();
    }

    // Shake animation for errors
    shakeElement(element) {
        element.classList.add('animate-shake');
        setTimeout(() => {
            element.classList.remove('animate-shake');
        }, 500);
    }

    // Typewriter effect
    typeWriter(element, text, speed = 50) {
        let i = 0;
        element.innerHTML = '';
        
        const type = () => {
            if (i < text.length) {
                element.innerHTML += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        };
        
        type();
    }

    // Progress bar animation
    animateProgressBar(progressBar, targetWidth, duration = 1000) {
        let startWidth = 0;
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const currentWidth = startWidth + (targetWidth - startWidth) * progress;
            progressBar.style.width = currentWidth + '%';
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.bantuinYukAnimations = new BantuinYukAnimations();
});

// Export for global use
window.BantuinYukAnimations = BantuinYukAnimations;

// Additional CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes confetti-fall {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
        }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .animate-glow {
        animation: glow 2s ease-in-out infinite;
    }

    .hero-animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }

    .hero-animate.animate-in {
        opacity: 1;
        transform: translateY(0);
    }

    .stagger-animate > * {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease-out;
    }

    .stagger-animate.animate-in > *:nth-child(1) { transition-delay: 0.1s; }
    .stagger-animate.animate-in > *:nth-child(2) { transition-delay: 0.2s; }
    .stagger-animate.animate-in > *:nth-child(3) { transition-delay: 0.3s; }
    .stagger-animate.animate-in > *:nth-child(4) { transition-delay: 0.4s; }
    .stagger-animate.animate-in > *:nth-child(5) { transition-delay: 0.5s; }

    .stagger-animate.animate-in > * {
        opacity: 1;
        transform: translateY(0);
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
`;
document.head.appendChild(style);
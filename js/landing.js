document.addEventListener('DOMContentLoaded', () => {
    // Initialize EmailJS
    emailjs.init("ULz19JfKOtOQ0DDVE"); // Substitua YOUR_PUBLIC_KEY pela sua chave pública do EmailJS

    // Smooth scrolling for navigation links
    const smoothScroll = () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                
                if (target) {
                    // Close mobile menu if open
                    const menu = document.querySelector('.menu');
                    if (menu && menu.classList.contains('active')) {
                        menu.classList.remove('active');
                        document.querySelector('.menu-toggle').classList.remove('active');
                    }

                    // Smooth scroll to target
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    };

    // Add animation to elements when they come into view
    const animateOnScroll = () => {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all cards, sections, and other elements
        document.querySelectorAll('.feature-card, .benefit-card, .login-box, .pricing-card, .security-content, .hero-content, .hero-image').forEach(element => {
            observer.observe(element);
        });
    };

    // Add scroll-based header effect
    const headerScrollEffect = () => {
        let lastScroll = 0;
        const header = document.querySelector('header');
        const headerHeight = header.offsetHeight;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll <= 0) {
                header.classList.remove('scroll-up', 'scroll-down');
                return;
            }
            
            if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
                header.classList.remove('scroll-up');
                header.classList.add('scroll-down');
            } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
                header.classList.remove('scroll-down');
                header.classList.add('scroll-up');
            }
            
            lastScroll = currentScroll;
        });
    };

    // Mobile menu functionality
    const mobileMenu = () => {
        const menuToggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('.menu');

        if (menuToggle && menu) {
            menuToggle.addEventListener('click', () => {
                menu.classList.toggle('active');
                menuToggle.classList.toggle('active');
                menuToggle.setAttribute('aria-expanded', menu.classList.contains('active'));
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !menuToggle.contains(e.target)) {
                    menu.classList.remove('active');
                    menuToggle.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    };

    // Add parallax effect to hero section
    const parallaxEffect = () => {
        const hero = document.querySelector('.hero');
        const floatingCards = document.querySelectorAll('.floating-card');

        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            if (hero) {
                hero.style.backgroundPositionY = `${scrolled * 0.5}px`;
            }
            floatingCards.forEach((card, index) => {
                const speed = 0.1 + (index * 0.05);
                card.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    };

    // Add hover effect to pricing cards
    const pricingCardHover = () => {
        const pricingCards = document.querySelectorAll('.pricing-card');
        
        pricingCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                pricingCards.forEach(c => c.classList.remove('featured'));
                card.classList.add('featured');
            });
        });
    };

    // Add typing effect to hero title
    const typingEffect = () => {
        const heroTitle = document.querySelector('.hero h1');
        if (!heroTitle) return;

        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        let i = 0;

        const type = () => {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(type, 50);
            }
        };

        type();
    };

    // Contact Form Handling
    const contactForm = document.querySelector('.contact-form form');
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData.entries());
            
            // Basic validation
            let isValid = true;
            const requiredFields = ['name', 'email', 'subject', 'message'];
            
            requiredFields.forEach(field => {
                const input = contactForm.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });
            
            // Email validation
            const emailInput = contactForm.querySelector('[name="email"]');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value.trim())) {
                isValid = false;
                emailInput.classList.add('error');
            }
            
            if (!isValid) {
                showNotification('Por favor, preencha todos os campos obrigatórios corretamente.', 'error');
                return;
            }
            
            try {
                // Show loading state
                const submitBtn = contactForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
                submitBtn.disabled = true;
                
                // Prepare template parameters
                const templateParams = {
                    from_name: data.name,
                    from_email: data.email,
                    phone: data.phone,
                    subject: data.subject,
                    message: data.message,
                    to_name: 'ShieldTech'
                };

                // Send email using EmailJS
                await emailjs.send(
                    'service_4a2f3gn', // Substitua pelo seu Service ID do EmailJS
                    'template_w7aejld', // Substitua pelo seu Template ID do EmailJS
                    templateParams
                );
                
                // Show success message
                showNotification('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
                
                // Reset form
                contactForm.reset();
                
            } catch (error) {
                console.error('Erro ao enviar email:', error);
                showNotification('Ocorreu um erro ao enviar a mensagem. Por favor, tente novamente.', 'error');
            } finally {
                // Reset button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
        
        // Add input event listeners for real-time validation
        const inputs = contactForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                input.classList.remove('error');
            });
        });
    }

    // Notification System
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        // Trigger animation
        setTimeout(() => notification.classList.add('show'), 10);
        
        // Remove notification after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Initialize all functions
    const init = () => {
        smoothScroll();
        animateOnScroll();
        headerScrollEffect();
        mobileMenu();
        parallaxEffect();
        pricingCardHover();
        typingEffect();
    };

    init();
}); 
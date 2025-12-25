/**
 * JavaScript principal pour les interactions du site
 */

// Gestion du slider
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');

function showSlide(n) {
    if (n >= slides.length) currentSlide = 0;
    if (n < 0) currentSlide = slides.length - 1;
    
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    if (slides[currentSlide]) {
        slides[currentSlide].classList.add('active');
    }
    if (dots[currentSlide]) {
        dots[currentSlide].classList.add('active');
    }
}

function changeSlide(n) {
    currentSlide += n;
    showSlide(currentSlide);
}

function currentSlideIndex(n) {
    currentSlide = n - 1;
    showSlide(currentSlide);
}

// Auto-play du slider
if (slides.length > 0) {
    setInterval(() => {
        changeSlide(1);
    }, 5000);
}

// Menu mobile
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const mainNav = document.getElementById('mainNav');

if (mobileMenuToggle && mainNav) {
    mobileMenuToggle.addEventListener('click', () => {
        mainNav.classList.toggle('active');
    });
}

// Ajouter au panier
function addToCart(produitId, quantite = 1) {
    quantite = parseInt(quantite) || 1;
    
    fetch('panier_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&produit_id=${produitId}&quantite=${quantite}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour le compteur du panier
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
            }
            
            // Afficher une notification
            showNotification('Produit ajouté au panier !', 'success');
        } else {
            showNotification('Erreur lors de l\'ajout au panier', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'ajout au panier', 'error');
    });
}

// Fonction pour afficher des notifications
function showNotification(message, type = 'success') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Styles de la notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white;
        border-radius: 4px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    // Ajouter l'animation CSS si elle n'existe pas
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Supprimer la notification après 3 secondes
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Validation des formulaires
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#e74c3c';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Veuillez remplir tous les champs obligatoires', 'error');
            }
        });
    });
});

// Animation au scroll (optionnel)
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observer les cartes de produits
document.querySelectorAll('.product-card, .category-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(card);
});

// Toggle sidebar sur mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarContent = document.getElementById('sidebarContent');
    
    if (sidebarToggle && sidebarContent) {
        sidebarToggle.addEventListener('click', function() {
            sidebarContent.classList.toggle('active');
        });
    }
});

// Fonction réutilisable pour initialiser un slider de catégories
function initCategoriesSlider(sliderId, prevBtnId, nextBtnId, dotsContainerId) {
    const categoriesSlider = document.getElementById(sliderId);
    if (!categoriesSlider) return;
    
    const track = categoriesSlider.querySelector('.categories-slider-track');
    const slides = track.querySelectorAll('.category-card-slide');
    const prevBtn = document.getElementById(prevBtnId);
    const nextBtn = document.getElementById(nextBtnId);
    const dotsContainer = document.getElementById(dotsContainerId);
    
    if (!track || slides.length === 0) return;
    
    let currentIndex = 0;
    let slidesToShow = getSlidesToShow();
    const totalSlides = slides.length;
    let maxIndex = Math.max(0, totalSlides - slidesToShow);
    let autoPlayInterval = null;
    let isPaused = false;
    
    // متغيرات للتمرير اليدوي (drag/scroll)
    let isDragging = false;
    let startX = 0;
    let scrollLeft = 0;
    let startY = 0;
    let isHorizontalScroll = false;
    let lastTouchTime = 0;
    
    // Créer les dots - un dot par catégorie
    if (dotsContainer) {
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('span');
            dot.className = 'slider-dot-category';
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                goToSlide(i);
                pauseAutoPlay();
                resumeAutoPlay();
            });
            dotsContainer.appendChild(dot);
        }
    }
    
    function getSlidesToShow() {
        const width = window.innerWidth;
        if (width < 768) return 1;
        if (width < 1024) return 2;
        if (width < 1280) return 3;
        return 4;
    }
    
    function updateSlider() {
        const slideWidth = slides[0].offsetWidth + 24; // width + gap
        const translateX = -currentIndex * slideWidth;
        track.style.transform = `translateX(${translateX}px)`;
        
        // Mettre à jour les dots - une dot par catégorie maintenant
        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.slider-dot-category');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }
        
        // Gérer les boutons
        if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
        if (nextBtn) nextBtn.style.opacity = currentIndex >= totalSlides - 1 ? '0.5' : '1';
    }
    
    function goToSlide(index) {
        currentIndex = Math.max(0, Math.min(index, totalSlides - 1));
        updateSlider();
    }
    
    function nextSlide() {
        if (currentIndex < totalSlides - 1) {
            // Avancer d'une seule catégorie à la fois
            currentIndex = currentIndex + 1;
        } else {
            // Retour au début si on est à la fin
            currentIndex = 0;
        }
        updateSlider();
    }
    
    function prevSlide() {
        if (currentIndex > 0) {
            // Reculer d'une seule catégorie à la fois
            currentIndex = currentIndex - 1;
        } else {
            // Aller à la fin si on est au début
            currentIndex = totalSlides - 1;
        }
        updateSlider();
    }
    
    // Auto-play toutes les 1 seconde
    function startAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(() => {
            if (!isPaused && totalSlides > 1) {
                nextSlide();
            }
        }, 1000); // 1 seconde = 1000ms
    }
    
    function pauseAutoPlay() {
        isPaused = true;
    }
    
    function resumeAutoPlay() {
        isPaused = false;
    }
    
    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            pauseAutoPlay();
            resumeAutoPlay();
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            pauseAutoPlay();
            resumeAutoPlay();
        });
    }
    
    // Pause au survol
    categoriesSlider.addEventListener('mouseenter', pauseAutoPlay);
    categoriesSlider.addEventListener('mouseleave', resumeAutoPlay);
    
    // Gestion du redimensionnement
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const newSlidesToShow = getSlidesToShow();
            if (newSlidesToShow !== slidesToShow) {
                slidesToShow = newSlidesToShow;
                maxIndex = Math.max(0, totalSlides - slidesToShow);
                currentIndex = Math.min(currentIndex, maxIndex);
                updateSlider();
                stopAutoPlay();
                startAutoPlay();
            } else {
                updateSlider();
            }
        }, 250);
    });
    
    // Initialiser
    updateSlider();
    startAutoPlay();
    
    // Centrer la catégorie active si elle existe
    if (document.querySelector('.category-card-slide.active')) {
        const activeSlide = document.querySelector('.category-card-slide.active');
        const activeIndex = Array.from(slides).indexOf(activeSlide);
        if (activeIndex !== -1) {
            currentIndex = activeIndex;
            updateSlider();
        }
    }
    
    // Navigation au clavier
    categoriesSlider.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            pauseAutoPlay();
            resumeAutoPlay();
        }
        if (e.key === 'ArrowRight') {
            nextSlide();
            pauseAutoPlay();
            resumeAutoPlay();
        }
    });
    
    // ============================================
    // نظام التمرير الأفقي فقط (Horizontal Scroll Only)
    // ============================================
    
    // منع التمرير العمودي عند السحب
    function handleTouchStart(e) {
        if (e.touches.length === 1) {
            // سحب بإصبع واحد
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isDragging = true;
            isHorizontalScroll = false;
            pauseAutoPlay();
            
            // الحصول على موضع التمرير الحالي
            scrollLeft = track.scrollLeft || 0;
        } else if (e.touches.length === 2) {
            // سحب بإصبعين - السماح بالتمرير الأفقي فقط
            startX = (e.touches[0].clientX + e.touches[1].clientX) / 2;
            startY = (e.touches[0].clientY + e.touches[1].clientY) / 2;
            isDragging = true;
            isHorizontalScroll = false;
            pauseAutoPlay();
        }
        
        lastTouchTime = Date.now();
    }
    
    function handleTouchMove(e) {
        if (!isDragging) return;
        
        if (e.touches.length === 1) {
            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const diffX = Math.abs(currentX - startX);
            const diffY = Math.abs(currentY - startY);
            
            // تحديد اتجاه التمرير
            if (!isHorizontalScroll && diffX > 5) {
                isHorizontalScroll = true;
            }
            
            // إذا كان التمرير أفقي، منع التمرير العمودي
            if (isHorizontalScroll || diffX > diffY) {
                e.preventDefault(); // منع التمرير العمودي
                
                const walk = (currentX - startX) * 1.5; // سرعة السحب
                track.scrollLeft = scrollLeft - walk;
            }
        } else if (e.touches.length === 2) {
            // سحب بإصبعين - تمرير أفقي فقط
            const currentX = (e.touches[0].clientX + e.touches[1].clientX) / 2;
            const currentY = (e.touches[0].clientY + e.touches[1].clientY) / 2;
            const diffX = Math.abs(currentX - startX);
            const diffY = Math.abs(currentY - startY);
            
            if (diffX > diffY) {
                e.preventDefault(); // منع التمرير العمودي
                isHorizontalScroll = true;
                
                const walk = (currentX - startX) * 1.5;
                track.scrollLeft = scrollLeft - walk;
            }
        }
    }
    
    function handleTouchEnd(e) {
        if (!isDragging) return;
        
        isDragging = false;
        isHorizontalScroll = false;
        
        // إعادة المزامنة مع currentIndex بعد السحب
        const slideWidth = slides[0].offsetWidth + 24;
        const newIndex = Math.round(-track.scrollLeft / slideWidth);
        currentIndex = Math.max(0, Math.min(newIndex, totalSlides - 1));
        updateSlider();
        
        resumeAutoPlay();
    }
    
    // دعم السحب بالماوس (Desktop)
    function handleMouseDown(e) {
        isDragging = true;
        startX = e.pageX - track.offsetLeft;
        scrollLeft = track.scrollLeft;
        pauseAutoPlay();
        track.style.cursor = 'grabbing';
        track.style.userSelect = 'none';
    }
    
    function handleMouseMove(e) {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - track.offsetLeft;
        const walk = (x - startX) * 1.5;
        track.scrollLeft = scrollLeft - walk;
    }
    
    function handleMouseUp() {
        isDragging = false;
        track.style.cursor = 'grab';
        track.style.userSelect = '';
        
        // إعادة المزامنة
        const slideWidth = slides[0].offsetWidth + 24;
        const newIndex = Math.round(-track.scrollLeft / slideWidth);
        currentIndex = Math.max(0, Math.min(newIndex, totalSlides - 1));
        updateSlider();
        
        resumeAutoPlay();
    }
    
    function handleMouseLeave() {
        isDragging = false;
        track.style.cursor = 'grab';
        track.style.userSelect = '';
    }
    
    // دعم عجلة الماوس للتمرير الأفقي
    function handleWheel(e) {
        // التحقق من أن التمرير أفقي (shift + wheel) أو تمرير أفقي مباشر
        if (e.shiftKey || Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
            e.preventDefault();
            track.scrollLeft += e.deltaX || e.deltaY;
            
            // تحديث currentIndex
            const slideWidth = slides[0].offsetWidth + 24;
            const newIndex = Math.round(-track.scrollLeft / slideWidth);
            currentIndex = Math.max(0, Math.min(newIndex, totalSlides - 1));
            updateSlider();
        }
    }
    
    // إضافة Event Listeners
    track.addEventListener('touchstart', handleTouchStart, { passive: false });
    track.addEventListener('touchmove', handleTouchMove, { passive: false });
    track.addEventListener('touchend', handleTouchEnd, { passive: true });
    track.addEventListener('touchcancel', handleTouchEnd, { passive: true });
    
    // دعم الماوس (Desktop)
    track.addEventListener('mousedown', handleMouseDown);
    track.addEventListener('mousemove', handleMouseMove);
    track.addEventListener('mouseup', handleMouseUp);
    track.addEventListener('mouseleave', handleMouseLeave);
    
    // دعم عجلة الماوس
    track.addEventListener('wheel', handleWheel, { passive: false });
    
    // تحسين CSS للسحب
    track.style.cursor = 'grab';
    
    // Nettoyer l'intervalle quand la page est cachée
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pauseAutoPlay();
        } else {
            resumeAutoPlay();
        }
    });
}

// Initialiser les sliders de catégories
document.addEventListener('DOMContentLoaded', function() {
    // Slider de la page catalogue
    initCategoriesSlider('categoriesSlider', 'categoriesPrev', 'categoriesNext', 'categoriesDots');
    
    // Slider de la page d'accueil
    initCategoriesSlider('categoriesSliderHome', 'categoriesPrevHome', 'categoriesNextHome', 'categoriesDotsHome');
});

// Initialiser les sliders de catégories
document.addEventListener('DOMContentLoaded', function() {
    // Slider de la page catalogue
    initCategoriesSlider('categoriesSlider', 'categoriesPrev', 'categoriesNext', 'categoriesDots');
    
    // Slider de la page d'accueil
    initCategoriesSlider('categoriesSliderHome', 'categoriesPrevHome', 'categoriesNextHome', 'categoriesDotsHome');
});

// Gestion de la quantité dans le panier
function updateQuantity(produitId, change) {
    const input = document.getElementById('qty-' + produitId);
    if (!input) return;
    
    let currentValue = parseInt(input.value) || 1;
    const max = parseInt(input.getAttribute('max')) || 999;
    const min = parseInt(input.getAttribute('min')) || 1;
    
    let newValue = currentValue + change;
    
    if (newValue < min) {
        newValue = min;
        return; // Ne pas soumettre si déjà au minimum
    }
    if (newValue > max) {
        newValue = max;
        return; // Ne pas soumettre si déjà au maximum
    }
    
    if (newValue !== currentValue) {
        input.value = newValue;
        // Soumettre le formulaire
        const form = input.closest('form');
        if (form) {
            // Ajouter un indicateur de chargement
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = '...';
            }
            form.submit();
        }
    }
}


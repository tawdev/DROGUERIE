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


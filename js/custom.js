/* ========================================
   CUSTOM JAVASCRIPT FOR GODZILLA PROJECT
   Cleaned, Modular, Optimized JS (ES6+)
   ======================================== */

// Global Variables
let cartBadgeElements = document.querySelectorAll('.cart-badge');

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', initApp);

function initApp() {
    initFloatingDots();
    initLoadingScreen();
    initCustomCursor();
    initNavbarScroll();
}

// Floating Dots Effect
function initFloatingDots() {
    const container = document.querySelector('.floating-dots');
    if (!container) return;

    const colors = ['#e67e22', '#f39c12', '#e4b95b', '#f1c40f'];
    
    function createDot() {
        const dot = document.createElement('div');
        dot.className = 'dot';
        dot.style.left = Math.random() * 100 + 'vw';
        dot.style.top = Math.random() * 100 + 'vh';
        dot.style.animationDuration = (Math.random() * 5 + 5) + 's';
        dot.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        container.appendChild(dot);

        setTimeout(() => dot.remove(), 10000);
    }

    // Initial dots
    for (let i = 0; i < 30; i++) createDot();
    setInterval(createDot, 300);
}

// Loading Screen
function initLoadingScreen() {
    window.addEventListener('load', () => {
        setTimeout(() => document.body.classList.add('loaded'), 2000);
    });
}

// Custom Cursor
function initCustomCursor() {
    const cursor = document.querySelector('.custom-cursor');
    if (!cursor) return;

    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';
    });
}

// Navbar Scroll Effect
function initNavbarScroll() {
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('nav1') || document.querySelector('.admin-navbar');
        if (window.pageYOffset > 50) {
            nav.style.background = 'rgba(0, 0, 0, 0.9)';
            nav.style.backdropFilter = 'blur(10px)';
        } else {
            nav.style.background = 'transparent';
            nav.style.backdropFilter = 'none';
        }
    });
}

// Smart Add to Cart
function smartAddToCart(productId, button) {
    button.style.transform = 'scale(0.95)';
    setTimeout(() => button.style.transform = 'scale(1)', 100);

    fetch(`cart.php?add=${productId}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast();
                updateCartBadges(data.cart_count);
                tempButtonSuccess(button);
            }
        })
        .catch(console.error);
}

function showToast() {
    // Toast implementation
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('active');
        setTimeout(() => toast.classList.remove('active'), 2000);
    }
}

function updateCartBadges(count) {
    cartBadgeElements.forEach(badge => {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    });
}

function tempButtonSuccess(button) {
    const original = button.innerHTML;
    button.innerHTML = '<i class="fa-solid fa-check"></i> Added!';
    button.style.background = '#ffc107';
    button.style.color = '#000';

    setTimeout(() => {
        button.innerHTML = original;
        button.style.background = '';
        button.style.color = '';
    }, 2000);
}

// Export for global use
window.smartAddToCart = smartAddToCart;


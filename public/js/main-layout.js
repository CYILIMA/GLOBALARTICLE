/**
 * Main Layout JavaScript
 * Handles theme toggling and subscription form submission
 */

// Theme Toggle Logic
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const html = document.documentElement;

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        themeIcon.textContent = '☀️';
    } else {
        html.classList.remove('dark');
        themeIcon.textContent = '🌙';
    }

    // Theme toggle event
    toggleBtn.addEventListener('click', () => {
        html.classList.toggle('dark');
        if (html.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
            themeIcon.textContent = '☀️';
        } else {
            localStorage.setItem('theme', 'light');
            themeIcon.textContent = '🌙';
        }
    });

    // Subscription Form Handler
    const subscribeForm = document.getElementById('subscribeForm');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const wrapper = document.getElementById('subscribeFormWrapper');
            const btn = document.getElementById('subBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoader = btn.querySelector('.btn-loader');
            const msgDiv = document.getElementById('subMessage');

            // Reset state
            msgDiv.classList.add('hidden');
            msgDiv.classList.remove('msg-success', 'msg-error');
            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'inline-block';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Success: Hide form and show message
                    wrapper.style.opacity = '0';
                    setTimeout(() => {
                        wrapper.classList.add('hidden');
                        msgDiv.textContent = data.message || 'Welcome to the circle! 🚀';
                        msgDiv.classList.remove('hidden');
                        msgDiv.classList.add('msg-success');
                    }, 300);
                } else {
                    // Error: Keep form and show error with shake
                    msgDiv.textContent = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Something went wrong.');
                    msgDiv.classList.remove('hidden');
                    msgDiv.classList.add('msg-error');

                    // Reset button
                    btn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnLoader.style.display = 'none';

                    // Remove shake after animation completes to allow re-triggering
                    setTimeout(() => {
                        msgDiv.classList.remove('msg-error');
                        void msgDiv.offsetWidth; // Trigger reflow
                        msgDiv.classList.add('msg-error');
                    }, 10);
                }
            } catch (error) {
                msgDiv.textContent = 'Connection lost. Please try again later.';
                msgDiv.classList.remove('hidden');
                msgDiv.classList.add('msg-error');
                btn.disabled = false;
                btnText.style.display = 'inline-block';
                btnLoader.style.display = 'none';
            }
        });
    }

    // Desktop "More" categories menu
    const moreTrigger = document.querySelector('.nav-dropdown-trigger');
    const moreDropdown = moreTrigger ? moreTrigger.closest('.nav-dropdown') : null;
    if (moreTrigger && moreDropdown) {
        moreTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const isOpen = moreDropdown.classList.toggle('open');
            moreTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (!moreDropdown.contains(e.target)) {
                moreDropdown.classList.remove('open');
                moreTrigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});

// Mobile Menu Toggle
function toggleMenu() {
    const nav = document.getElementById('mobileNav');
    const overlay = document.getElementById('navOverlay');
    const isOpening = !nav.classList.contains('active');

    nav.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = isOpening ? 'hidden' : '';
}

// Close nav on Escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const nav = document.getElementById('mobileNav');
        if (nav.classList.contains('active')) toggleMenu();

        const moreDropdown = document.querySelector('.nav-dropdown.open');
        const moreTrigger = document.querySelector('.nav-dropdown-trigger');
        if (moreDropdown) {
            moreDropdown.classList.remove('open');
            moreTrigger?.setAttribute('aria-expanded', 'false');
        }
    }
});

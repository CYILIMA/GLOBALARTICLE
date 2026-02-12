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
            const btn = document.getElementById('subBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoader = btn.querySelector('.btn-loader');
            const msgDiv = document.getElementById('subMessage');

            // Reset state
            msgDiv.style.display = 'none';
            msgDiv.className = 'subscribe-message';
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

                msgDiv.style.display = 'block';
                if (response.ok) {
                    msgDiv.textContent = data.message || 'Subscribed successfully!';
                    msgDiv.classList.add('msg-success');
                    form.reset();
                } else {
                    msgDiv.textContent = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Subscription failed.');
                    msgDiv.classList.add('msg-error');
                }
            } catch (error) {
                msgDiv.style.display = 'block';
                msgDiv.textContent = 'Something went wrong. Please try again.';
                msgDiv.classList.add('msg-error');
            } finally {
                btn.disabled = false;
                btnText.style.display = 'inline-block';
                btnLoader.style.display = 'none';
            }
        });
    }
});

// Mobile Menu Toggle
function toggleMenu() {
    const nav = document.getElementById('mobileNav');
    nav.classList.toggle('active');
}

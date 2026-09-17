/**
 * SPARTA-PW - Login Page Script
 */

function updateLoginThemeUI(theme) {
    const icon = document.getElementById('themeIconLogin');
    if (!icon) return;
    if (theme === 'dark') {
        icon.className = 'fa-solid fa-sun text-warning';
    } else {
        icon.className = 'fa-solid fa-moon text-dark';
    }
}

function toggleLoginTheme() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('sparta_theme', newTheme);
    updateLoginThemeUI(newTheme);
}

document.addEventListener('DOMContentLoaded', function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    updateLoginThemeUI(currentTheme);
});

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    if (!passwordInput || !toggleIcon) return;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

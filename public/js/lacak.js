/**
 * SPARTA-PW - Lacak Status Pengajuan Script
 */

function updateLacakThemeUI(theme) {
    const icon = document.getElementById('themeIconLacak');
    if (!icon) return;
    if (theme === 'dark') {
        icon.className = 'fa-solid fa-sun text-warning';
    } else {
        icon.className = 'fa-solid fa-moon text-dark';
    }
}

function toggleLacakTheme() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('sparta_theme', newTheme);
    updateLacakThemeUI(newTheme);
}

document.addEventListener('DOMContentLoaded', function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    updateLacakThemeUI(currentTheme);
});

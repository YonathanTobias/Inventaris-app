// Theme Switcher Functions
function updateThemeUI(theme) {
    const icon = document.getElementById('themeIcon');
    if (!icon) return;
    if (theme === 'dark') {
        icon.className = 'fa-solid fa-sun text-warning';
    } else {
        icon.className = 'fa-solid fa-moon text-white';
    }
}

function toggleAppTheme() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('sparta_theme', newTheme);
    updateThemeUI(newTheme);
}

// Global Delete Confirmation Function
function confirmDelete(event, formElement, itemName = 'item ini') {
    event.preventDefault();
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus ${itemName}? Data yang terhapus tidak dapat dikembalikan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B91C1C',
            cancelButtonColor: '#475569',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                formElement.submit();
            }
        });
    } else {
        if (confirm(`Apakah Anda yakin ingin menghapus ${itemName}?`)) {
            formElement.submit();
        }
    }
    return false;
}

// Initialize tooltips & theme on load
document.addEventListener("DOMContentLoaded", function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    updateThemeUI(currentTheme);

    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

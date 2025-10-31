import './bootstrap';

// Livewire and Alpine are provided via @fluxScripts in the layout

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            const isOpen = mobileMenu.classList.contains('hidden');

            if (isOpen) {
                // Open menu
                mobileMenu.classList.remove('hidden');
                hamburgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                // Close menu
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });
    }
});

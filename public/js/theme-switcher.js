// public/js/theme-switcher.js
document.addEventListener('DOMContentLoaded', () => {
    const themeSwitcher = document.getElementById('theme-switcher');
    const htmlElement = document.documentElement;

    // Function to set the theme
    const setTheme = (theme) => {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeSwitcher) {
            themeSwitcher.innerHTML = theme === 'dark' 
                ? '<i class="bi bi-sun-fill"></i>' 
                : '<i class="bi bi-moon-fill"></i>';
        }
    };

    // Check for saved theme in localStorage or user's system preference
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const currentTheme = savedTheme ? savedTheme : (prefersDark ? 'dark' : 'light');

    // Apply the initial theme
    setTheme(currentTheme);

    // Add click event listener to the switcher
    if (themeSwitcher) {
        themeSwitcher.addEventListener('click', () => {
            const newTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    }
});

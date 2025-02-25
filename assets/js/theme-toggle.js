document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('theme') || 'light';

    document.body.classList.add(currentTheme + '-mode');

    toggleButton.addEventListener('click', function () {
        const newTheme = document.body.classList.contains('light-mode') ? 'dark' : 'light';
        document.body.classList.toggle('light-mode');
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', newTheme);
    });
});
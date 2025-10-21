import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  const themeToggle = document.getElementById('theme-toggle')
  const html = document.documentElement

  const savedTheme = localStorage.getItem('theme')
  if (savedTheme === 'dark') html.classList.add('dark')

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      html.classList.toggle('dark')
      const isDark = html.classList.contains('dark')
      localStorage.setItem('theme', isDark ? 'dark' : 'light')
    })
  }
})
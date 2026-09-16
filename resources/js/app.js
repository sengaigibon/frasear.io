import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if (document.querySelector('[data-phrase-slider]')) {
    import('./phrase-slider.js');
}
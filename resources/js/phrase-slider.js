import { Swiper } from 'swiper';
import { EffectFade, Keyboard, Navigation, A11y } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const container = document.querySelector('[data-phrase-slider]');

if (container) {
    new Swiper(container, {
        modules: [EffectFade, Keyboard, Navigation, A11y],
        effect: 'fade',
        fadeEffect: { crossFade: true },
        loop: true,
        keyboard: { enabled: true },
        a11y: {
            enabled: true,
            prevSlideMessage: 'Frase anterior',
            nextSlideMessage: 'Frase siguiente',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        speed: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 300,
    });
}
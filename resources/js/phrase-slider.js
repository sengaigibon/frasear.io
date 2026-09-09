import { Swiper } from 'swiper';
import {EffectFade, Keyboard, A11y, Navigation, Pagination} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

const container = document.querySelector('[data-phrase-slider]');

if (container) {
    new Swiper(container, {
        modules: [EffectFade, Keyboard, A11y, Navigation, Pagination],
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
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        speed: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 300,
    });
}
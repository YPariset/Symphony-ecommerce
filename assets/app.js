import './styles/style.scss';
import './scripts/item';
import modularLoad from 'modularload';
import LocomotiveScroll from 'locomotive-scroll';

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

let scroll = new LocomotiveScroll({
  el: document.querySelector('[data-scroll-container]'),
  smooth: true,
  multiplier: 1.2,
});

const load = new modularLoad({
  enterDelay: 900,
});

load.on('loaded', () => {
  scroll.destroy();
  scroll = new LocomotiveScroll({
    el: document.querySelector('[data-scroll-container]'),
    smooth: true,
    multiplier: 1.2,
  });
});
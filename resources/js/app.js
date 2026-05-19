import './bootstrap';
import './tiptap-editor';
import Lenis from 'lenis';
import { animate, scroll, inView, spring, stagger } from 'motion';

// 1. Expose Motion globally for use in Blade / Alpine components
window.motion = { animate, scroll, inView, spring, stagger };

// 2. Initialize Lenis smooth scrolling
const lenis = new Lenis({
  duration: 1.2,
  easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  direction: 'vertical',
  gestureDirection: 'vertical',
  smooth: true,
  smoothTouch: false,
  touchMultiplier: 2,
});

function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

window.lenis = lenis;

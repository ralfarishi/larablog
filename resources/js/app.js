import './bootstrap';
import './tiptap-editor';
import Lenis from 'lenis';
import { animate, scroll, inView, spring, stagger } from 'motion';

// 1. Expose Motion globally for use in Blade / Alpine components
window.motion = { animate, scroll, inView, spring, stagger };

// 2. Initialize Lenis smooth scrolling (v2 API)
const lenis = new Lenis({
  duration: 1.2,
  easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  orientation: 'vertical',
  gestureOrientation: 'vertical',
  smoothWheel: true,
  touchMultiplier: 2,
});

// 3. RAF loop with cancel handle to prevent memory leaks
let rafId;
function raf(time) {
  lenis.raf(time);
  rafId = requestAnimationFrame(raf);
}
rafId = requestAnimationFrame(raf);

// 4. Cleanup on page hide (tab switch, back/forward navigation)
window.addEventListener('pagehide', () => {
  cancelAnimationFrame(rafId);
  lenis.destroy();
});

// 5. Cleanup on Livewire page navigations (if using navigate)
document.addEventListener('livewire:navigating', () => {
  cancelAnimationFrame(rafId);
});
document.addEventListener('livewire:navigated', () => {
  rafId = requestAnimationFrame(raf);
});

window.lenis = lenis;

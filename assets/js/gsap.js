const tl = gsap.timeline();

tl.from('.upText div', {y:100, stagger:0.1, duration:.2});


tl.to('.transition .toUp', {y:100, stagger:.1, duration:.1, delay: 1})
.to('.transition', {x:-2000, opacity: 0, duration:.3})
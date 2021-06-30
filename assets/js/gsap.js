const tl = new gsap.timeline();

tl.from('.upText div', {y:100, stagger:0.1, duration:.2});

tl.from('.parentContainer .containerSujet', {x:-1500, opacity: 0, stagger:0.1, duration:.5})
    .from('.rightCome', {x: 2000, duration:.5});

tl.to('.transitionPage .toUp', {y:100, stagger:.1, duration:.1, delay: 1})
.to('.transitionPage', {x:-2000, opacity: 0, duration:.3}) 

tl.from('.opac', {opacity: 0, duration: .5})
    

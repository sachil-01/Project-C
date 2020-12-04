var navSlide = () => {
	const burger = document.querySelector('.burger');
	const nav = document.querySelector('.nav-links');
	const navLinks = document.querySelectorAll('.nav-links li');
	let menuOpen = false;

	const tnsCarousel = document.querySelectorAll('.slidertns');
		tnsCarousel.forEach(slider => {
		const tnsSlider = tns({
			container: slider,
			autoplay: true,
			autoplayButtonOutput: false,
			items: 1,
			slideBy: 'page',
			mouseDrag: true,
			controls: false,
			navPosition: 'bottom'                   
		});
	});


	burger.addEventListener('click', ()=> {

		// Animate links
		navLinks.forEach((link, index) => {
			if(link.style.animation){
				link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.2}s`;
			} else {
				link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.2}s`;
			}
		});

		// Toggle nav
		nav.classList.toggle('nav-active');

		// Burger animation
		burger.classList.toggle('toggle');
	});
}

var app = () => {
	navSlide();
}

app();

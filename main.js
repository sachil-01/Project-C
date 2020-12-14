var profileTabsBtn = document.getElementById('ads-blogs-btns')
    var userAdsBtn = document.getElementById('ad-btn')
    var userBlogsBtn = document.getElementById('blog-btn')

    function leftClick(){
        profileTabsBtn.style.left= '0';
        userAdsBtn.style.color= 'white';
        userBlogsBtn.style.color= 'gray';
	document.getElementById("userBlogsList").style.cssText = "display: none;";
	document.getElementById("userAdsList").style.cssText = "display: block;";
    }

    function rightClick(){
        profileTabsBtn.style.left= '50%'
        userAdsBtn.style.color= 'gray'
        userBlogsBtn.style.color= 'white'
	document.getElementById("userBlogsList").style.cssText = "display: block;";
	document.getElementById("userAdsList").style.cssText = "display: none;";
    }

var navSlide = () => {
	const burger = document.querySelector('.burger');
	const nav = document.querySelector('.nav-links');
	const navLinks = document.querySelectorAll('.nav-links li');
	let menuOpen = false;

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

var slider = () => {

	const tnsSlider = tns({
		container: '.slidertns',
		autoplay: true,
		autoplayButtonOutput: false,
		items: 1,
		slideBy: 'page',
		mouseDrag: true,
		controls: false,
		navPosition: 'bottom'                   
	});
}

var app = () => {
	navSlide();
	slider();
}

app();

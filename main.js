var displayUserFunc = document.getElementById('adminUserFunc')
var displayAdvertisementFunc = document.getElementById('adminAdvertisementFunc')
var displayBlogpostFunc = document.getElementById('adminBlogpostFunc')
var allChosenFunc = ["adminDisplayUserFunc", "adminDisplayAdvertisementFunc", "adminDisplayBlogpostFunc"];
var allChosenFuncTitles = ["Gebruikers", "Advertenties", "Blogposts"];

function adminDisplayFunc(chosenFunc){
	//checks which function is chosen. If not chosen -> display is none.
	for(i=0; i < allChosenFunc.length; i++){
		if(allChosenFunc[i] != chosenFunc){
			document.getElementById(allChosenFunc[i]).style.cssText = "display: none;";
		} else {
			//display result (list) of chosen function
			document.getElementById(allChosenFunc[i]).style.cssText = "display: block;";
			//set title at top of list
			document.getElementById("chosenAdminFuncTitle").innerHTML = allChosenFuncTitles[i];
		}
	}
	document.getElementById("adminDisplayFuncList").style.cssText = "display: none;";
	document.getElementById("adminDisplayResultList").style.cssText = "display: block;";
}

//return to display of all admin functionalities
function adminDisplayAllFunc(){
	document.getElementById("adminDisplayFuncList").style.cssText = "display: block;";
	document.getElementById("adminDisplayResultList").style.cssText = "display: none;";
}

//var adminSearchInput = document.getElementById('adminSearchInput')

//function adminSearch(){
//	document.getElementById("adminSearchBtn").innerHTML = adminSearchInput.value;
//}

// Search filters
function showHideAdvanceSearch() {
	if(document.getElementById("advanced-search-box").style.display=="none") {
		document.getElementById("advanced-search-box").style.display = "block";
		document.getElementById("advance_search_submit").value= "1";
	} else {
		document.getElementById("advanced-search-box").style.display = "none";
		document.getElementById("with_the_exact_of").value= "";
		document.getElementById("without").value= "";
		document.getElementById("starts_with").value= "";
		document.getElementById("search_in").value= "";
		document.getElementById("advance_search_submit").value= "";
	}
}


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

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

//DELETE BLOGPOST ADMIN FUNCTION
//blogpostId is the id of the blogpost stored in the button value
function adminDeleteBlogpost(blogpostId, blogpostUser){
    $.ajax({
        url: "adminFunctions.php",
        type: 'post',
        data: {function: "blogpost", id: blogpostId, user: blogpostUser},
        success: function(result)
        {
            //checks which list needs to be updated
            if(blogpostUser == "adminBlogpost"){ //admin blogpost list
                document.getElementById("adminDisplayBlogpostFunc").innerHTML = result;
            } else { // registered user blogpost list
                document.getElementById("userBlogsList").innerHTML = result;
                document.getElementById("totalblogs").innerHTML = (result.match(/Blogtitel:/g) || []).length;
                document.getElementById("starttotalblogs").innerHTML = "";
            }
        }
    })
}
//DELETE ADVERTISEMENT ADMIN FUNCTION
function adminDeleteAdvertisement(advertisementId, advertisementUser){
    $.ajax({
        url: "adminFunctions.php",
        type: 'post',
        data: {function: "advertisement", id: advertisementId, user: advertisementUser},
        success: function(result)
        {
            //checks which list needs to be updated
            if(advertisementUser == "adminAdvertisement"){ //admin advertisement list
                document.getElementById("adminDisplayAdvertisementFunc").innerHTML = result;
            } else { // registered user advertisement list
                document.getElementById("userAdsList").innerHTML = result;
                document.getElementById("totalads").innerHTML = (result.match(/Advertentienaam:/g) || []).length;
                document.getElementById("starttotalads").innerHTML = "";
            }
        }
    })
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
	})
	//add 'type' attribute to every button to avoid submitting form when clicking the button
	var items = tnsSlider.getInfo();
	const keys = Object.keys(items.navItems)
	for (const key of keys) {
		items.navItems[key].type = 'button';
	}
}

var app = () => {
	navSlide();
	slider();
}

app();

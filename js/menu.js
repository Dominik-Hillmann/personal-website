/***** MENU *****/
// this menu contains everything concerning the menu on the left

// first: positioning the menu correctly
var menu = document.getElementById("menuwrapper");
function adaptMenu() {
   menu.style.marginTop = ((window.innerHeight / 2) - (menu.offsetHeight / 2) /*- document.getElementById("weather").offsetHeight*/) + "px";
}

adaptMenu(); // adapting for the first time after loading
window.addEventListener("resize", adaptMenu); // every time the window size is changed

/***** ANIMATIONS CONCERNING MENU *****/
// let all a's transition if one of them transitions
var header = document.getElementsByTagName("header")[0];
// var menuLinks = menu.getElementsByTagName("a");
var menuLinks = menu.getElementsByTagName("p");
// show menu options when hovering above header
header.addEventListener("mouseover", function () {
   for (link of menuLinks) {
      link.classList.remove("unshown");
      link.classList.add("shown");
   }
});
// hide menu options when the cursor leaves the header
header.addEventListener("mouseleave", function () {
   for (link of menuLinks) {
      link.classList.remove("shown");
      link.classList.add("unshown");
   }
});

menuLinks[0].addEventListener("click", function () {
   // START
   document.querySelector("#start").scrollIntoView({
      behavior: "smooth"
   });
});

menuLinks[1].addEventListener("click", function () {
   // SKILLS
   document.querySelector("#skills").scrollIntoView({
      behavior: "smooth"
   });
});

menuLinks[2].addEventListener("click", function () {
   // CONTACT
   document.querySelector("#contact").scrollIntoView({
      behavior: "smooth"
   });
});


/***** PRELOADING IMAGES *****/
function preload(url) {
   var img = new Image();
   img.src = url;
   return img;
}

var fullCircle = preload("images/circle_full.png");
var hollowCircle = preload("images/hollow_circle.png");

// change picture
var menuDivs = menu.getElementsByTagName("div");
var imgs = [];

for (var i = 0; i < menuDivs.length; i++) {
   imgs.push(menuDivs[i].getElementsByTagName("img"));
}
// why not just loop over the array index? Problems with creating functions within loop referencing wrong index
menuDivs[0].addEventListener("mouseover", function () { imgs[0][0].src = "images/circle_full.png"; });
menuDivs[0].addEventListener("mouseleave", function () {
   imgs[0][0].src = "images/hollow_circle.png";
   setMenuImg();
});
menuDivs[1].addEventListener("mouseover", function () { imgs[1][0].src = "images/circle_full.png"; });
menuDivs[1].addEventListener("mouseleave", function () {
   imgs[1][0].src = "images/hollow_circle.png";
   setMenuImg();
});
menuDivs[2].addEventListener("mouseover", function () { imgs[2][0].src = "images/circle_full.png"; });
menuDivs[2].addEventListener("mouseleave", function () {
   imgs[2][0].src = "images/hollow_circle.png";
   setMenuImg();
});

/* Everything concerning the switch of images because of scrolling */
function setMenuImg() {
   // checks for scrolling position and set the right image in the menu
   let currentLine = this.scrollY;
   resetMenu();

   if (currentLine <= helloDivStart + 10) {
      setFullCircle(0);
   } else if (currentLine < contactStart) {
      setFullCircle(1);
   } else {
      setFullCircle(2);
   }
}

function resetMenu() {
   // set all of the menu images to hollow circles
   imgs[0][0].src = "images/hollow_circle.png";
   imgs[1][0].src = "images/hollow_circle.png";
   imgs[2][0].src = "images/hollow_circle.png";
}

function setFullCircle(i) {
   // set image full circle at image i
   imgs[i][0].src = "images/circle_full.png";
}

function getY(e) {
   // source: https://stackoverflow.com/questions/288699/get-the-position-of-a-div-span-tag
   for (
      var y = 0;
      e != null;
      y += e.offsetTop, e = e.offsetParent
   );
   return y;
}


let docHeight = Math.max(document.body.scrollHeight, document.body.offsetHeight, document.documentElement.clientHeight, document.documentElement.scrollHeight, document.documentElement.offsetHeight);
let screenHeight = screen.height;
let line = Math.round(screenHeight / 2);
// position in px where a certain div starts within the document
let helloDivStart = getY(document.getElementById("hello"));
let skillSliderStart = getY(document.getElementById("skillSlider"));
let contactStart = getY(document.getElementById("contact"));

document.body.onscroll = setMenuImg;
setMenuImg();

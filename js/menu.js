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
menuDivs[0].addEventListener("mouseleave", function () { imgs[0][0].src = "images/hollow_circle.png"; });
menuDivs[1].addEventListener("mouseover", function () { imgs[1][0].src = "images/circle_full.png"; });
menuDivs[1].addEventListener("mouseleave", function () { imgs[1][0].src = "images/hollow_circle.png"; });
menuDivs[2].addEventListener("mouseover", function () { imgs[2][0].src = "images/circle_full.png"; });
menuDivs[2].addEventListener("mouseleave", function () { imgs[2][0].src = "images/hollow_circle.png"; });


let body = document.body;
let html = document.documentElement;

let docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);



// document.body.onscroll = function () {
//     console.log(this.scrollY); //check the number in console
// }


// let e = [e_1, e_2, e_3, e_4];
// for (e_i of e) {
//    console.log(e_i, getPos(e_i));
// }
// // IDEE: prozentualer Anteil bis zum Anfang des nächsten Teils als Benchmark für jede Bildschirmgröße --> in absolute Werte übertragen
//
// let relativeDivStarts = [];
// for (e_i of e) {
//    console.log("relaitve height: ", e_i, getPos(e_i).y / docHeight);
// }

console.log("ScreenHeight: ", {x: screen.width, y: screen.height});

// on window resize, calculate relative heights anew
// wenn obere Kante des Elements über die "Mittellinie des Bildschirms gerät", wird das Bild gewechselt

let line = Math.round(screen.height / 2);
// console.log("HelloHeight: ", e_1.height);
/*
if (line > e_2) {
   zweites Bild;
} else if (line > e_3) {
   drittes Bild;
} else {
   erstes Bild;
}
*/
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

function getPos(e) {
   // source: https://stackoverflow.com/questions/288699/get-the-position-of-a-div-span-tag
   for (
      var lx = 0, ly = 0;
      e != null;
      lx += e.offsetLeft, ly += e.offsetTop, e = e.offsetParent
   );
   return {x: lx, y: ly};
}

let screenHeight = screen.height;
let helloDivStart = getPos(document.getElementById("hello")).y;
let skillSliderStart = getPos(document.getElementById("skillSlider")).y;
let contactStart = getPos(document.getElementById("contact")).y;

document.body.onscroll = function () {
   console.log(this.scrollY);

   let currentLine = this.scrollY + Math.round(screenHeight / 2);

   resetMenu();

   if (currentLine > skillSliderStart && currentLine < contactStart) {
      setFullCircle(1);
      console.log({test: 1});
   } else if (currentLine > skillSliderStart && currentLine != 0) {
      setFullCircle(2);
      console.log({test: 2});
   } else {
      setFullCircle(0);
      console.log({test: 0});
   }
}

/***** MENU *****/
// this menu contains everything concerning the menu on the left

// first: positioning the menu correctly
var menu = document.getElementById("menuwrapper");

function adaptmenu()
{
   menu.style.marginTop = ((window.innerHeight / 2) - menu.offsetHeight / 2) + "px";
}

adaptmenu(); // adapting for the first time after loading
window.addEventListener("resize", adaptmenu); // every time the window size is changed


// secondly: animations when hovering
// let all a's transition if one transitions
var header = document.getElementsByTagName("header")[0];
var menuLinks = menu.getElementsByTagName("a");
// show menu options when hovering above header
header.addEventListener("mouseover", function()
{
   for(link of menuLinks)
   {
      link.classList.remove("unshown");
      link.classList.add("shown");
   }
});
// hide menu options when the cursor leaves the header
header.addEventListener("mouseleave", function()
{
   for(link of menuLinks)
   {
      link.classList.remove("shown");
      link.classList.add("unshown");
   }
});


// change picture
var menuDivs = menu.getElementsByTagName("div");
var imgs = [];

for(var i = 0; i < menuDivs.length; i++)
   imgs.push(menuDivs[i].getElementsByTagName("img"));

// why not just loop over the array index? Problems with creating functions within loop referencing wrong index

/***** WICHTIG: das hier ändern, sodass eine Klasse geaendert wird, damit der Uebergang smotth geschehen kann *****/
menuDivs[0].addEventListener("mouseover", function() { imgs[0][0].src = "images/circle_full.png"; });
menuDivs[0].addEventListener("mouseleave", function() { imgs[0][0].src = "images/hollow_circle.png"; });

menuDivs[1].addEventListener("mouseover", function() { imgs[1][0].src = "images/circle_full.png"; });
menuDivs[1].addEventListener("mouseleave", function() { imgs[1][0].src = "images/hollow_circle.png"; });

menuDivs[2].addEventListener("mouseover", function() { imgs[2][0].src = "images/circle_full.png"; });
menuDivs[2].addEventListener("mouseleave", function() { imgs[2][0].src = "images/hollow_circle.png"; });

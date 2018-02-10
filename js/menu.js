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
//var menu = document.getElementById("menuwrapper");
var menuLinks = menu.getElementsByTagName("a");

header.addEventListener("mouseover", function()
{
   for(link of menuLinks)
   {
      link.classList.remove("unshown");
      link.classList.add("shown");
   }
});

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
console.log(menuDivs);
console.log("images: ", imgs);

for(var i = 0; i < imgs.length; i++)
{
   var img = imgs[i];
   menuDivs[i].addEventListener("mouseover", function()
   {
      imgs.src = "";
   });
}

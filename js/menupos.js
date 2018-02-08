var menu = document.getElementById("menuwrapper");

function adaptmenu()
{
   menu.style.marginTop = ((window.innerHeight / 2) - menu.offsetHeight / 2) + "px";
}

adaptmenu(); // adapting for the first time after loading
window.addEventListener("resize", adaptmenu); // every time the window size is changed

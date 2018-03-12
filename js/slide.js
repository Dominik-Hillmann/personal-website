// pushes last element to the right and lets the next Element slide in from the left
function slide() {
   let slides = document.getElementsByClassName("slide");

   for (let i = 0; i < slides.length; i++) {
      slide[i].setAttribute("style", "display: none");
   }
}

var doSlide = {
   sliderForOne : function () { },
   sliderForTwo : function () { }
};

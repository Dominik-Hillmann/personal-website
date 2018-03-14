const NUM_PICS = 3; // meaning 3 images in the row

// pushes last element to the right and lets the next Element slide in from the left
var slide = function () {


   let slides = document.getElementsByClassName("slide");
      console.log("Es gibt ", slides.length, " Bildelemente");

   let currSlides = [];
   let currSlidesInd = [];
   for (let i = nextStop; i > (nextStop - NUM_PICS); i--) {
      if (i < 0) {
         currSlides.push(slides[i + slides.length]);
         currSlidesInd.push(i + slides.length);
      } else {
         currSlides.push(slides[i]);
         currSlidesInd.push(i);
      }
   }

   let nextSlides = [];
   let nextSlidesInd = [];
   for (let i = nextStop; i < (nextStop + NUM_PICS); i++) {
      if (i > slides.length) {
         nextSlides.push(slides[i - slides.length]);
         nextSlidesInd.push(i - slides.length);
      } else {
         nextSlides.push(slides[i]);
         nextSlidesInd.push(i);
      }
   }

   nextStop += NUM_PICS;
   nextStop = ((nextStop + NUM_PICS) > slides.length) ? (nextStop + NUM_PICS - slides.length) : (nextStop + NUM_PICS);

   console.log("CurrentSlides: ", currSlides, currSlidesInd);

   console.log("nextSlides: ", nextSlides, nextSlidesInd);

   console.log("NextStop: ", nextStop);



   // rein: slides[cnt - 1] --> slideIn, fadeIn, nach links
   // raus: slides[cnt] --> .slideOut, fadeout, nach rechts
}

var nextStop = 0; // for the start: 0 to the NUM

/*var doSlide = {
   sliderForOne : function () { },
   sliderForTwo : function () { }
};*/

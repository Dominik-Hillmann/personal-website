const NUM_ROW = 3; // meaning 3 images in the row

// BUG: if any number further apart than 1, then leave normally ordered big numbers in front
// returns the imgs from allImgArr with indeces indArr
var indToImg = function (indArr, allImgArr) {
   let imgArr = [];
   for (index of indArr) {
      imgArr.push(allImgArr[index]);
   }
   return imgArr;
}


var switchClass = function (slide, inClass, outClass) {
   slide.classList.add(inClass);
   slide.classList.remove(outClass);
}


// next row of imgs that come after those currently displayed
var getNextSlides = function () {
   let slides = document.getElementsByClassName("slide");//"slide"
   console.log("Es gibt ", slides.length, " Bildelemente");

   let currSlidesInd = [];
   for (let i = nextStop; i > (nextStop - NUM_ROW); i--) {
      if (i < 0) {
         currSlidesInd.push(i + slides.length);
      } else {
         currSlidesInd.push(i);
      }
   }

   currSlidesInd.sort(function (a, b) {
      return a - b;
   });
   let currSlides = indToImg(currSlidesInd, slides);

   nextStop = ((nextStop + NUM_ROW) >= slides.length) ? (nextStop + NUM_ROW - slides.length) : (nextStop + NUM_ROW);

   console.log("CurrentSlides: ", currSlides, currSlidesInd);
   console.log("NextStop: ", nextStop);

   return currSlides;
}


// pushes last element to the right and lets the next Element slide in from the left
var animateSlide = function () {
   let allSlides = document.getElementsByClassName("slide");
   let nextSlides = getNextSlides();


   for (slide of allSlides) {
      switchClass(slide, "slideOut", "slideIn");
   }

   // timeout because the slideOut animation in main.css takes 800ms and I want new ones to slide in after that
   setTimeout(function () {
      for (slide of allSlides) {
         switchClass(slide, "notShown", "slideShown");
      }

      for (nextSlide of nextSlides) {
         switchClass(nextSlide, "slideShown", "notShown");
         switchClass(nextSlide, "slideIn", "slideOut");
      }
   }, 800);
}


// to be executed before animateSlide so that there are slides having class slideShown
var firstSlides = function () {
   //let slides = getNextSlides();
   for (slide of getNextSlides()) {
      switchClass(slide, "slideShown", "notShown");
      switchClass(slide, "slideIn", "slideOut");
   }
}

var nextStop = NUM_ROW - 1;
firstSlides();

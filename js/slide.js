const NUM_ROW = 3; // meaning 3 images in the row

// for sorting arrs of ints smallest first
var sortInd = function (a, b) { return a - b; }

// returns the imgs from allImgArr with indeces indArr
var indToImg = function (indArr, allImgArr) {
   let imgArr = [];
   for (index of indArr) {
      imgArr.push(allImgArr[index]);
   }
   return imgArr;

   // BUG: if any number further apart than 1, then leave normally ordered big numbers in front
}

var switchClass = function (slide, inClass, outClass) {
   slide.classList.add(inClass);
   slide.classList.remove(outClass);
}

var getNextSlides = function () {
   let slides = document.getElementsByClassName("slide");
      console.log("Es gibt ", slides.length, " Bildelemente");

   let currSlidesInd = [];
   for (let i = nextStop; i > (nextStop - NUM_ROW); i--) {
      if (i < 0) {
         currSlidesInd.push(i + slides.length);
      } else {
         currSlidesInd.push(i);
      }
   }

   let nextSlidesInd = [];
   for (let i = nextStop; i < (nextStop + NUM_ROW); i++) {
      if (i > slides.length) {
         nextSlidesInd.push(i - slides.length);
      } else {
         nextSlidesInd.push(i);
      }
   }

   nextSlidesInd.sort(sortInd);
   currSlidesInd.sort(sortInd);
   let currSlides = indToImg(currSlidesInd, slides);
   let nextSlides = indToImg(nextSlidesInd, slides);

   nextStop = ((nextStop + NUM_ROW) >= slides.length) ? (nextStop + NUM_ROW - slides.length) : (nextStop + NUM_ROW);

   console.log("CurrentSlides: ", currSlides, currSlidesInd);
   console.log("NextStop: ", nextStop);

   return currSlides;
}

// pushes last element to the right and lets the next Element slide in from the left
var animateSlide = function () {
   let currSlides = document.getElementsByClassName("slideShown");
   let nextSlides = getNextSlides();

   console.log("CURRENT SLIDES ", currSlides);

   for (currSlide of currSlides) {
      switchClass(currSlide, "slideOut", "slideIn");
      setTimeout(function () {
         switchClass(currSlide, "notShown", "slideShown");
      }, 800); // 800ms = 0.8s, time needed to slide out in css/main.css
   }

   for (nextSlide of nextSlides) {
      switchClass(nextSlide, "slideShown", "notShown");
      switchClass(nextSlide, "slideIn", "slideOut");
   }
}

var showFirstSlides = function () {
   let slides = getNextSlides();
   for (slide of slides) {
      switchClass(slide, "slideShown", "notShown");
      switchClass(slide, "slideIn", "slideOut");
   }
}

//var emptySlides = document.getElementsByClassName("empty");
var nextStop = NUM_ROW - 1; // for the start: 0 to the NUM
showFirstSlides();

/*var doSlide = {
   sliderForOne : function () { },
   sliderForTwo : function () { }
};*/

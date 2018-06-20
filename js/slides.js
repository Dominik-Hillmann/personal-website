const NUM_ROW = 3; // meaning 3 images in the row

/***** Switching the images *****/

function indToImg(indArr, allImgArr) {
   // BUG: if any number further apart than 1, then leave normally ordered big numbers in front
   // returns the imgs from allImgArr with indeces indArr
   let imgArr = [];
   for (index of indArr) {
      imgArr.push(allImgArr[index]);
   }
   return imgArr;
}


function switchClass(slide, inClass, outClass) {
   slide.classList.add(inClass);
   slide.classList.remove(outClass);
}


function getNextSlides(type) {
   // next row of imgs that come after those currently displayed
   // Yes, I know it's ugly!
   let slides = document.getElementsByClassName(type);
   // console.log("Es gibt ", slides.length, " Bildelemente");

   let currSlidesInd = [];
   switch (type) {
      case "web":
         for (let i = webStop; i > (webStop - NUM_ROW); i--) {
            if (i < 0) {
               currSlidesInd.push(i + slides.length);
            } else {
               currSlidesInd.push(i);
            }
         }
         webStop = ((webStop + NUM_ROW) >= slides.length) ? (webStop + NUM_ROW - slides.length) : (webStop + NUM_ROW);
         break;

      case "data":
         for (let i = dataStop; i > (dataStop - NUM_ROW); i--) {
            if (i < 0) {
               currSlidesInd.push(i + slides.length);
            } else {
               currSlidesInd.push(i);
            }
         }
         dataStop = ((dataStop + NUM_ROW) >= slides.length) ? (dataStop + NUM_ROW - slides.length) : (dataStop + NUM_ROW);
         break;

      case "theo":
         for (let i = theoStop; i > (theoStop - NUM_ROW); i--) {
            if (i < 0) {
               currSlidesInd.push(i + slides.length);
            } else {
               currSlidesInd.push(i);
            }
         }
         theoStop = ((theoStop + NUM_ROW) >= slides.length) ? (theoStop + NUM_ROW - slides.length) : (theoStop + NUM_ROW);
         break;

      case "general":
         for (let i = generalStop; i > (generalStop - NUM_ROW); i--) {
            if (i < 0) {
               currSlidesInd.push(i + slides.length);
            } else {
               currSlidesInd.push(i);
            }
         }
         generalStop = ((generalStop + NUM_ROW) >= slides.length) ? (generalStop + NUM_ROW - slides.length) : (generalStop + NUM_ROW);
         break;
   }

   // currSlidesInd.sort(function (a, b) { return a - b; });
   let currSlides = indToImg(currSlidesInd, slides);

   // console.log("CurrentSlides: ", currSlides, currSlidesInd);
   // console.log("NextStop: ", webStop, dataStop, generalStop, theoStop);

   return currSlides;
}


function animateSlide(type) {
   // pushes last element to the right and lets the next Element slide in from the left

   let allSlides = document.getElementsByClassName(type);
   let nextSlides = getNextSlides(type);

   // if infoText open, close it before showing next types
   let infoText = document.getElementById(type + "Info");
   if (infoText.classList.contains("slideShown")) {
      // first, let the infoText vanish, then put in the new slides
      switchClass(infoText, "slideOut", "slideIn");

      setTimeout(function () {
         switchClass(infoText, "notShown", "slideShown");

         for (nextSlide of nextSlides) {
            switchClass(nextSlide, "slideShown", "notShown");
            switchClass(nextSlide, "slideIn", "slideOut");
         }
      }, 500);

   } else {
      // let all the currently displayed slides vanish and then put in the new slides
      for (slide of allSlides) {
         switchClass(slide, "slideOut", "slideIn");
      }

      setTimeout(function () {
         // timeout because the slideOut animation in main.css takes 800ms and I want new ones to slide in after that
         for (slide of allSlides) {
            switchClass(slide, "notShown", "slideShown");
         }

         for (nextSlide of nextSlides) {
            switchClass(nextSlide, "slideShown", "notShown");
            switchClass(nextSlide, "slideIn", "slideOut");
         }
      }, 500);
   }
   return;
}


function firstSlides(type) {
   // to be executed before animateSlide so that there are slides having class slideShown
   for (slide of getNextSlides(type)) {
      switchClass(slide, "slideShown", "notShown");
      switchClass(slide, "slideIn", "slideOut");
   }
}

/***** EXECUTION OF SOME FUNCTIONS *****/

let types = ["web", "data", "general"]; // "theo", "general"];
let webStop = NUM_ROW - 1;
let dataStop = NUM_ROW - 1;
let theoStop = NUM_ROW - 1;
let generalStop = NUM_ROW - 1;

for (type of types) {
   firstSlides(type);
}

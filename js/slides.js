const NUM_ROW = 3; // meaning 3 images in the row

/***** Progess bars at top of sliders *****/
/*
var roundToMultOf = function (to, num) {
   let rest = num % to;
   return (rest > (to / 2)) ? (num + to - rest) : (num - rest);
}


// returns the nearest percentage divisible by 5 depending on what's the last pic in slider out of all pics
var percSlide = function (type) {
   let allImgs = document.getElementsByClassName(type);
   let totLen = allImgs.length;

   let lastImg; // index of last img containing class "slideShown"
   for (let i = 0; i < totLen; i++) {
      if (allImgs[i].classList.contains("slideShown") && allImgs[(i == allImgs.length - 1) ? 0 : (i + 1)].classList.contains("notShown")) {
         lastImg = i;
      }
   }

   console.log("LastImg", lastImg, allImgs[lastImg]);
   return roundToMultOf(5, (lastImg / totLen) * 100);
}
*/
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
   let slides = document.getElementsByClassName(type);
   console.log("Es gibt ", slides.length, " Bildelemente");

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

   currSlidesInd.sort(function (a, b) { return a - b; });
   let currSlides = indToImg(currSlidesInd, slides);

   console.log("CurrentSlides: ", currSlides, currSlidesInd);
   console.log("NextStop: ", webStop, dataStop, generalStop, theoStop);

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

      // timeout because the slideOut animation in main.css takes 800ms and I want new ones to slide in after that
      setTimeout(function () {
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
   return;
}


var types = ["web"]/*, "data", "theo", "general"]*/;
var webStop = NUM_ROW - 1;
var dataStop = NUM_ROW - 1;
var theoStop = NUM_ROW - 1;
var generalStop = NUM_ROW - 1;

for (type of types) {
   firstSlides(type);
}

/***** More Information on skills *****/

var webInfo = "Text that will be shown.";
var dataInfo = "Text that will be shown.";
var theoInfo = "Text that will be shown.";
var generalInfo = "Text that will be shown.";

function showTypeInfo(type) {
   // show text that describes what kind of skills are shown here
   let slider = document.getElementsByClassName(type + "-slider-wrapper")[0];
   let question = slider.getElementsByClassName("question")[0];
   let allSlides = slider.getElementsByClassName("slide");
   let infoText = slider.getElementsByClassName("infoText")[0];
   let currSlides = slider.getElementsByClassName("slideShown");
   let navButtons = slider.getElementsByClassName("navSlider");

   console.log("Type", type);
   console.log("Slider", slider);
   console.log("Question", question);
   console.log("allSlides", allSlides);

   // möglicherweise immer wieder Elemente auswählen, weil sich die current elements ändern
   for (slide of currSlides) {
      switchClass(slide, "slideOut", "slideIn");
   }

   setTimeout(function () {
      for (allSlide of allSlides) {
         switchClass(allSlide, "notShown", "slideShown");
      }

      switchClass(infoText, "slideShown", "notShown");
      switchClass(infoText, "slideIn", "slideOut");
   }, 500);

   // remove eventlisteners from both buttons
   // add to them the retrun to the last current pcittures
   // onclick they get their old event listeners back
   return currSlides;
   // returns slides there were hidden so that the next function can use them to make them visible again
}

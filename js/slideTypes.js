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
var getNextSlides = function (type) {
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


// pushes last element to the right and lets the next Element slide in from the left
var animateSlide = function (type) {
   let allSlides = document.getElementsByClassName(type);
   let nextSlides = getNextSlides(type);

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


// to be executed before animateSlide so that there are slides having class slideShown
var firstSlides = function (type) {
   for (slide of getNextSlides(type)) {
      switchClass(slide, "slideShown", "notShown");
      switchClass(slide, "slideIn", "slideOut");
   }
}

var webStop = NUM_ROW - 1;
var dataStop = NUM_ROW - 1;
var theoStop = NUM_ROW - 1;
var generalStop = NUM_ROW - 1;

firstSlides("web");
firstSlides("data");
firstSlides("theo");
firstSlides("general");



/***** Progess bars at top of sliders *****/

// turns HTMLcollection into arr so prototype array methods can be used
var toArr = function (collection) {
   let reArr = [];
   for (div of collection) {
      reArr.push(div);
   }
   return reArr;
}

var roundToMultOf = function (to, num) {
   let rest = num % to;
   return (rest > (to / 2)) ? (num + to - rest) : (num - rest) ;
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
   return roundToMultOf(10, (lastImg / totLen) * 100);
}

var animBar = function (type) {
   
}

console.log(percSlide("web"));

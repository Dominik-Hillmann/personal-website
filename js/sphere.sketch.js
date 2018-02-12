// declaring global variables that will be defined in the functions

// essential variables for construction of the sphere
var sphereRadius;                                    // how big is the radius of the circle representing sphere
var cursor;
var startPoint;
var middlePoint;
var layers = [];
var cg;                                              // color gradient to be chosen
const COLOR_GRADIENTS =
[
   new ColorGradient(243, 29,  52,  247, 159, 39),
   new ColorGradient(14,  255, 255, 204, 255, 19),
   new ColorGradient(10,  48,  255, 16,  216, 255)
];

// vars for DOM
var width;
var height;
var canvas;                                     // reference to canvas here, to be styled

var imgHeight;
var imgWidth;
var imgOpacity = 255;
var arrow;

function windowResized() // event: resizing canvas so that it fits into resized window
{
   //width = windowWidth;
   //height = windowHeight;
   //setupOnResize();
   //resizeCanvas(windowWidth - (windowWidth * 0.02), windowHeight);
   setupOnResize();
}

function setup()
{
   /*width = windowWidth;
   height = windowHeight;
   sphereRadius = Math.round(0.5 * windowWidth); // new window size --> new radius
   sphereRadius -= Math.round(0.1 * windowWidth);
   middlePoint = new Position(width / 2, height / 2);

   canvas = createCanvas(width - (0.01 * width), height);
   canvas.parent("sketch-holder");
   */
   sphereRadius = Math.round(0.5 * windowWidth); // new window size --> new radius
   sphereRadius -= Math.round(0.2 * windowWidth);                           // few px less wide because otherwiese points missing at sphere's "equator"
   layers = [];                                  // therefore clear all layers
   width = windowWidth;
   height = windowHeight;                        // new width and height
   middlePoint = new Position(width / 2, height / 2);

   canvas = createCanvas(width - (0.02 * width), height);        // reset old canvas
   canvas.parent("sketch-holder");
   /*
   arrow = loadImage("images/arrow_down.png");
   imgHeight = 2 * sphereRadius;
   imgWidth = 2 * sphereRadius;
   */
   cursor = new Cursor(winMouseX, winMouseY, width);

   // frist collect all points possible, and for each of them the distance to the middle point
   var circlePositions = []; // needed to capture all possible points on canvas that may belong to circle
   var circleDists = []; // needed to save the distance between possible points and the desired middle point of the sphere
   for(var x = 1; x <= width; x++)
   {
      for(var y = 1; y <= height; y++)
      {
         circlePositions.push(new Position(x, y));
         circleDists.push(distance(x, y, middlePoint));
      }
   }

   // filter for all points circlePositions, that have a distance (saved in circleDists) of sphereRadius to middle point
   circlePositions = circlePositions.filter(function(element, i, array)
   {
      return circleDists[i] === sphereRadius;
   });

   // filters for all points with sphereRadius distance to middle point on the left side of the canvas
   var leftPositions = circlePositions.filter(function(element, i, array)
   {
      return element.x < width / 2;
   });
   // filters for all points with sphereRadius distance to middle point on the right side of the canvas
   var rightPositions = circlePositions.filter(function(element, i, array)
   {
      return element.x > width / 2;
   });
   // none of the filters uses <= or >= because those points directly on the edge won't be needed anyways

   // for the last filter, both arrays need to have the same number of points in them
   if(leftPositions.length != rightPositions.length)
      console.error("unequal length of arrays to build layers");
   else
   {
      var sortedByYPos;
      for(var i = 1; i <= height; i++) // first, get all points that have the same height (.y)
      {
         sortedByYPos = circlePositions.filter(function(element, index, array)
         {
            return element.y === i;
         });

         if(sortedByYPos.length === 0) // if there are no circle points with the y-position of i, go to the next iteration
            continue;

         /*
            * the points were sorted into the original array so that
            * the points most on the left (or the smallest .x) were sorted in first
            * the points on the top of the canvas (smalles .y) were sorted in first
            * --> therefore, the left border of one height is the first element and right border is the last element
         */

         layers.push(new Layer
         (
            sortedByYPos[0].x,
            sortedByYPos[0].y,
            sortedByYPos[sortedByYPos.length - 1].x,
            sortedByYPos[sortedByYPos.length - 1].y
         ));
      } // for

      // detaching first and last layer because they caused problem in the animation
      layers.splice(0, 1);
      layers.splice(layers.length - 1, 1);

      // colorizing the dots with a random color gradient
      var r = Math.random();

      if(r < (1 / COLOR_GRADIENTS.length))
         cg = COLOR_GRADIENTS[0];
      else if(r < (2 / COLOR_GRADIENTS.length))
         cg = COLOR_GRADIENTS[1];
      else
         cg = COLOR_GRADIENTS[2];

      // now the starting point for the color gradient has to be set up
      startPoint = layers[Math.round(0.75 * layers.length)].right;
      // colorizing with startPoint from before as starting position for color gradient
      for(var i = 0; i < layers.length; i++)
      {
         layers[i].addPoints(1);
         layers[i].colorize(startPoint, cg);
      } // for
   } // else
} // setup

function draw()
{
   background(7, 7, 7);
   cursor.update();

   for(var i = 0; i < layers.length; i++)
   {
      layers[i].movePoints(cursor);
      layers[i].drawPoints(1, 7, width);
   }
   /*
   tint(255, imgOpacity);
   if(imgOpacity >= 0)
   {
      imgOpacity -= 15;
      image
      (
         arrow,
         (width / 2) - (imgWidth / 2),
         (height / 2) - (imgHeight / 2),
         imgWidth,
         imgHeight
      );

   }
   */
}

// This is a setup function that is called if the browser window is being resized.
// It is slightly different from setup, e.g. it keeps the color gradient that was set in setup() and does not choose a new one.
function setupOnResize()
{
   sphereRadius = Math.round(0.5 * windowWidth); // new window size --> new radius
   sphereRadius -= Math.round(0.2 * windowWidth);                           // few px less wide because otherwiese points missing at sphere's "equator"
   layers = [];                                  // therefore clear all layers
   width = windowWidth;
   height = windowHeight;                        // new width and height
   middlePoint = new Position                    // new middle point because of new size
   (
      Math.round(width / 2),
      Math.round(height / 2)
   );

   canvas = createCanvas(width - (0.02 * width), height);        // reset old canvas
   canvas.parent("sketch-holder");
   // canvas.position(0, 0);
   // canvas.style("z-index", "-1");               // entirely new resized canvas too
   imgHeight = 2 * sphereRadius;
   imgWidth = 2 * sphereRadius;
   cursor = new Cursor(winMouseX, winMouseY, width);

   // frist collect all points possible, and for each of them the distance to the middle point
   var circlePositions = []; // needed to capture all possible points on canvas that may belong to circle
   var circleDists = []; // needed to save the distance between possible points and the desired middle point of the sphere
   for(var x = 1; x <= width; x++)
   {
      for(var y = 1; y <= height; y++)
      {
         circlePositions.push(new Position(x, y));
         circleDists.push(distance(x, y, middlePoint));
      }
   }

   // filter for all points circlePositions, that have a distance (saved in circleDists) of sphereRadius to middle point
   circlePositions = circlePositions.filter(function(element, i, array)
   {
      // if(circleDists[i] == sphereRadius) console.log("!!!");
      return circleDists[i] == sphereRadius;
   });

   // filters for all points with sphereRadius distance to middle point on the left side of the canvas
   var leftPositions = circlePositions.filter(function(element, i, array)
   {
      return element.x < width / 2;
   });
   //console.log("leftPositions: ", leftPositions.length);
   // filters for all points with sphereRadius distance to middle point on the right side of the canvas
   var rightPositions = circlePositions.filter(function(element, i, array)
   {
      return element.x > width / 2;
   });
   // none of the filters uses <= or >= because those points directly on the edge won't be needed anyways

   // for the last filter, both arrays need to have the same number of points in them
   while(leftPositions.length != rightPositions.length)
   {
      var lenR = rightPositions.length;
      var lenL = leftPositions.length;

      if(lenR > lenL)
         rightPositions.splice(lenR - 1, 1);
      else if(lenR < lenL)
         leftPositions.splice(lenL - 1, 1);
   }

   var sortedByYPos;
   for(var i = 1; i <= height; i++) // first, get all points that have the same height (.y)
   {
      sortedByYPos = circlePositions.filter(function(element, index, array)
      {
         return element.y === i;
      });

      if(sortedByYPos.length === 0) // if there are no circle points with the y-position of i, go to the next iteration
         continue;
      /*
         * the points were sorted into the original array so that
         * the points most on the left (or the smallest .x) were sorted in first
         * the points on the top of the canvas (smallest .y) were sorted in first
         * --> therefore, the left border of one height is the first element and right border is the last element
      */
      layers.push(new Layer
      (
         sortedByYPos[0].x,
         sortedByYPos[0].y,
         sortedByYPos[sortedByYPos.length - 1].x,
         sortedByYPos[sortedByYPos.length - 1].y
      ));
   } // for

   layers.splice(0, 1);
   layers.splice(layers.length - 1, 1);

   // now the starting point for the color gradient has to be set up
   startPoint = layers[Math.round(0.75 * layers.length)].right;
   // colorizing with startPoint from before as starting position for color gradient
   for(var i = 0; i < layers.length; i++)
   {
      layers[i].addPoints(1);
      layers[i].colorize(startPoint, cg); // cg chosen once in setup, not after resize
   } // for
} // setupOnResize()

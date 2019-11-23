const SPHERE_RADIUS = 255;
const WIDTH = window.innerHeight - 15; // -15 so there will be no scrollbars
const HEIGHT = window.innerHeight - 15;

var cursor;
var startPoint;
var middlePoint = new Position(WIDTH / 2, HEIGHT / 2);
var layers = [];
var followCursor = document.getElementById("follow");
var colorGradient =
[
   new ColorGradient(243, 29, 52, 247, 159, 39),
   new ColorGradient(14, 255, 255, 204, 255, 19),
   new ColorGradient(10, 48, 255, 16, 216, 255)
];

function setup()
{
   var canvas = createCanvas(WIDTH, HEIGHT);
   canvas.parent("sketch-holder");
   cursor = new Cursor(winMouseX, winMouseY);

   // frist collect all points possible, and for each of them the distance to the middle point
   var circlePositions = []; // needed to capture all possible points on canvas that may belong to circle
   var circleDists = []; // needed to save the distance between possible points and the desired middle point of the sphere
   for(var x = 1; x <= WIDTH; x++)
   {
      for(var y = 1; y <= HEIGHT; y++)
      {
         circlePositions.push(new Position(x, y));
         circleDists.push(distance(x, y, middlePoint));
      }
   }

   // filter for all points circlePositions, that have a distance (saved in circleDists) of SPHERE_RADIUS to middle point
   circlePositions = circlePositions.filter(function(element, i, array)
   {
      return circleDists[i] === SPHERE_RADIUS;
   });

   // filters for all points with SPHERE_RADIUS distance to middle point on the left side of the canvas
   var leftPositions = circlePositions.filter(function(element, i, array)
   {
      return element.x < WIDTH / 2;
   });
   // filters for all points with SPHERE_RADIUS distance to middle point on the right side of the canvas
   var rightPositions = circlePositions.filter(function(element, i, array)
   {
      return element.x > WIDTH / 2;
   });
   // none of the filters uses <= or >= because those points directly on the edge won't be needed anyways

   // for the last filter, both arrays need to have the same number of points in them
   if(leftPositions.length != rightPositions.length)
      console.error("unequal length of arrays to build layers");
   else
   {
      var sortedByYPos;
      for(var i = 1; i <= HEIGHT; i++) // first, get all points that have the same height (.y)
      {
         sortedByYPos = circlePositions.filter(function(element, index, array)
         {
            return element.y === i;
         });

         if(sortedByYPos.length === 0) // if there are no circle points with the y-position of i, go to the next iteration
            continue;
         /* the points were sorted into the original array so that
            the points most on the left (or the smallest .x) were sorted in first
            the points on the top of the canvas (smalles .y) were sorted in first
            --> therefore, the left border of one height is the first element and right border is the last element */
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


      // colorizing the dots with a color gradient
      var r = Math.random();
      if(r < (1 / colorGradient.length))
         colorGradient = colorGradient[0];
      else if(r < (2 / colorGradient.length))
         colorGradient = colorGradient[1];
      else
         colorGradient = colorGradient[2];
      // now the starting point for the color gradient has to be set up
      startPoint = layers[Math.round(0.75 * layers.length)].right;
      // colorizing with startPoint from before as starting position for color gradient
      for(var i = 0; i < layers.length; i++)
      {
         layers[i].addPoints(2);
         layers[i].colorize(startPoint, colorGradient);
      } // for
   } // else
} // setup

function draw()
{
   background(0, 0, 0);

   cursor.update();
   for(var i = 0; i < layers.length; i++)
   {
      layers[i].movePoints(cursor);
      layers[i].drawPoints(2, 6);
   }
}

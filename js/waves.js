var width = displayWidth;
var height = displayHeight;

function setup() {
   createCanvas(width, height);
}

function draw() {
   blackground(0);

   for (x = 1; x <= width; x += 2) {
      point(x, Math.sin(x));
   }
}

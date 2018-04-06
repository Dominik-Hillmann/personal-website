var width; 
var height;

function setup() {
   width = Math.round(displayWidth * 0.38);
   height = displayHeight;
   var cnv = createCanvas(width, height);
   cnv.parent("sketch");
}

function draw() {
   background(0, 155, 155);
}

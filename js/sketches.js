var width;
var height;
var cnv;

function setup() {
   width = Math.round(displayWidth * 0.38);
   height = displayHeight;
   cnv = createCanvas(width, height);
   cnv.parent("sketch");
}

function draw() {
   background(0, 155, 155);
}

function windowResized() {
   /*width = Math.round(displayWidth * 0.38);
   height =  displayHeight;
   // resizeCanvas(width, height);
   cnv.size(width, height);
   console.log("Resized");*/
   setupOnResize();
}

function setupOnResize() {
   /*width = Math.round(displayWidth * 0.38);
   height = displayHeight;
   cnv = createCanvas(width, height);
   cnv.parent("sketch");*/
}
/*
window.onresize = function() {
   resizeCanvas(width, height);
}
*/

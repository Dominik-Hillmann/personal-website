var cnv;
const PERC_WIDTH = 0.38;

function setup() {
   cnv = createCanvas(Math.round(PERC_WIDTH * displayWidth), displayHeight);
   cnv.parent("sketch");
}

function draw() {
   background(0, 200, 100);
}

function windowResized() {
   // use displayWidth und displayHeight später für Berechnung der Grafik
   resizeCanvas(Math.round(PERC_WIDTH * windowWidth), windowHeight);
}

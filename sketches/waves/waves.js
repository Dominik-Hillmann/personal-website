class Color {
   constructor(r, g, b) {
      this.r = r;
      this.g = g;
      this.b = b;
   }
} // Color

class Slider {
   // A slider ping-pongs a value between its min and its max.
   constructor(min, max, step) {
      this.min = min;
      this.max = max;
      this.mean = Math.round((min + max) / 2);

      this.step = step;
      // value starts at a random position within range => curves will almost always look a bit different
      this.current = this.randBetween(min, max);

      this.down = true;
      this.curFrame = 0;
   }

   curr(frame) {
      // returns current value and updates its position between min and max
      // the check for the frame is useful to not update the slider several times within one
      if (this.curFrame != frame) {
         this.current += this.down ? -this.step : this.step;
         this.down = (this.current < this.min || this.current > this.max) ? !this.down : this.down;
         this.curFrame = frame;
      }
      return this.current;
   }

   randBetween(min, max) {
      return Math.random() * (max - min) + min;
   }
} // Slider


class Wave {
   constructor(speed, meanHeight, wind, spike) {
      // I know it is ugly, but these magic numbers needed to be put in by hand in order to see what looks good
      // the sliders ping-pong the parameters around a sin curve and make its movement look somewhat natural or random
      this.speed = new Slider(speed - 0.01, speed + 0.01, 0.0001);
      this.vert = 0;

      this.meanHeight = meanHeight;

      this.wind = new Slider(wind - 0.01, wind, 0.00001);
      this.spike = new Slider(spike - 20, spike + 20, 0.1);
   }

   drawWave(frameCnt, thickness) {
      this.vert += this.speed.curr(frameCnt);

      for (let x = 1; x <= width; x++) {
         let y = this.spike.curr(frameCnt) * Math.sin(this.wind.curr(frameCnt) * x + this.vert) + this.meanHeight;
         ellipse(x, y, thickness);
      }
   }
} // Wave


let cnv; // needs to visible within draw() and setup(), therefore cannot be declared there
let waves = [];
let possibleColors = [[
      // green-blue
      new Color(163, 201, 168),
      new Color(105, 162, 151),
      new Color(80, 128, 142)
   ], [
      // green
      new Color(49, 87, 44),
      new Color(79, 119, 45),
      new Color(144, 169, 85)
   ], [
      // reds
      new Color(100, 13, 20),
      new Color(128, 14, 19),
      new Color(209, 0, 41)
   ]
];

let colors = possibleColors[Math.floor(Math.random() * possibleColors.length)];

const NUM_WAVES = colors.length;
const PERC_WIDTH = 0.38;

// functions for p5
function setup() {
   frameRate(30);
   cnv = createCanvas(Math.round(PERC_WIDTH * displayWidth), displayHeight);
   cnv.parent("sketch");

   for (let i = 0; i < NUM_WAVES; i++) {
      // again magic numbers, needed to know what intuitively looks good
      waves.push(new Wave(.01, height / 2, .01, 40));
   }
} // setup()

function draw() {
   background(255, 255, 255/*, 20*/);

   for (let i = 0; i < NUM_WAVES; i++) {
      let col = colors[i];
      stroke(col.r, col.g, col.b);
      fill(col.r, col.g, col.b);
      waves[i].drawWave(frameCount, 2);
   }
} // draw()

function resize() {
   resizeCanvas(Math.round(PERC_WIDTH * windowWidth), windowHeight);
} // resize()

class Slider {
   constructor(min, max, step) {
      this.min = min;
      this.max = max;
      this.mean = Math.round((min + max) / 2);

      this.step = step;
      this.current = this.mean;

      this.down = true;
      this.curFrame = 0;
   }

   mean() {
      return this.mean;
   }

   getCurr(frame) {
      // returns current value and updates its position between min and max
      if (this.curFrame != frame) {
         this.current += this.down ? -this.step : this.step;
         /*if (this.current < this.min || this.current > this.max) this.down = !this.down;*/
         this.down = (this.current < this.min || this.current > this.max) ? !this.down : this.down;
         this.curFrame = frame;
      }
      return this.current;
   }
}


class Wave {

   constructor(speed, meanHeight) {
      this.vert = Math.random()/* * NOCHWASEINTRAGEN*/;

      this.spike = Math.random() /**/;
      this.wind = Math.random();

      this.speed = speed;
      this.meanHeight = meanHeight;
   }

   sin(x, wind, spike, hori, vert) {
      return hori + spike * Math.sin(wind * x + vert);
   }

   drawWave(frameCnt) {
      this.vert += this.speed;

      for (let x = 1; x <= width; x++) {
         let y = sin(x, this.wind, this.spike, this.meanHeight, this.vert);
         let yPlus = sin(x + 1, this.wind, this.spike, this.meanHeight, this.vert);
         line(x, y, x + 1, yPlus);
      }
   }
}



var cnv;
const PERC_WIDTH = 0.38;
var waves = [];

function setup() {
   cnv = createCanvas(Math.round(PERC_WIDTH * displayWidth), displayHeight);
   cnv.parent("sketch");
   waves.push(new Wave());
}

function draw() {
   background(255, 255, 255);
   //fill(255, 255, 255);
   for (let x = 1; x <= width; x++) {
      line(x, Math.sin(x) + 200, x + 1, Math.sin(x + 1) + 200);
   }

   for (wave of waves) {

   }
}

function resize() {
   resizeCanvas(Math.round(PERC_WIDTH * windowWidth), windowHeight);
}

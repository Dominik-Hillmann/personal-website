class Slider {
   // A slider ping-pongs a value between its min and its max.
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

   constructor(speed, meanHeight, wind, spike) {
      this.speed = speed;
      this.vert = 0;

      this.meanHeight = meanHeight;

      this.wind = new Slider(wind - 5, wind + 5, 0.01);
      this.spike = spike;
   }

   drawWave(frameCnt) {
      // this.vert += this.speed;
      for (let x = 1; x <= width; x++) {
         line(
            x,
            this.spike * Math.sin(this.wind.getCurr(frameCnt) * x + this.vert) + this.meanHeight,
            x + 1,
            this.spike * Math.sin(this.wind.getCurr(frameCnt) * (x + 1) + this.vert) + this.meanHeight
         );
      }
   }
}



let cnv;
const PERC_WIDTH = 0.38;
let waves = [];

function setup() {
   frameRate(30);
   cnv = createCanvas(Math.round(PERC_WIDTH * displayWidth), displayHeight);
   cnv.parent("sketch");

   waves.push(new Wave(0.01, height / 3, 40, 50));
   // waves.push(new Wave(1, height / 2, 100, 300));
}

function draw() {
   background(255, 255, 255);
   //fill(255, 255, 255);
   // for (let x = 1; x <= width; x++) {
      // line(
      //    x,
      //    100 * Math.sin(x) + (height/2),
      //    x + 1,
      //    100 * Math.sin(x + 1) + (height/2)
      // );
   // }

   for (wave of waves) {
      wave.drawWave(frameCount);
   }
}

function resize() {
   resizeCanvas(Math.round(PERC_WIDTH * windowWidth), windowHeight);
}

class Slider {
   // A slider ping-pongs a value between its min and its max.
   constructor(min, max, step) {
      this.min = min;
      this.max = max;
      this.mean = Math.round((min + max) / 2);

      this.step = step;
      this.current = this.mean; // RANDOM STARTER EINBAUEN

      this.down = true;
      this.curFrame = 0;
   }

   curr(frame) {
      // returns current value and updates its position between min and max
      if (this.curFrame != frame) {
         this.current += this.down ? -this.step : this.step;
         this.down = (this.current < this.min || this.current > this.max) ? !this.down : this.down;
         this.curFrame = frame;
      }
      return this.current;
   }
}


class Wave {

   constructor(speed, meanHeight, wind, spike) {
      // I know this is ugly, but these parameters need to be put in by hand in order to see what looks good.
      this.speed = new Slider(speed - 0.01, speed + 0.01, 0.0001);
      this.vert = 0;

      this.meanHeight = meanHeight;

      this.wind = new Slider(wind - 0.01, wind, 0.00001);
      this.spike = new Slider(spike - 20, spike + 20, 0.1);
   }

   drawWave(frameCnt) {
      this.vert += this.speed.curr(frameCnt);
      for (let x = 1; x <= width; x++) {
         line(
            x,
            this.spike.curr(frameCnt) * Math.sin(this.wind.curr(frameCnt) * x + this.vert) + this.meanHeight,
            x + 1,
            this.spike.curr(frameCnt) * Math.sin(this.wind.curr(frameCnt) * (x + 1) + this.vert) + this.meanHeight
         );
      }
   }

} // Wave



let cnv;
const PERC_WIDTH = 0.38;
let waves = [];

function setup() {
   frameRate(30);
   cnv = createCanvas(Math.round(PERC_WIDTH * displayWidth), displayHeight);
   cnv.parent("sketch");

   waves.push(new Wave(0.01, height / 3, 0.01, 40));
   // waves.push(new Wave(0.06, 2 * height / 2, 0.02, 40));
}

function draw() {
   background(255, 255, 255);

   for (wave of waves) {
      wave.drawWave(frameCount);
   }
}

function resize() {
   resizeCanvas(Math.round(PERC_WIDTH * windowWidth), windowHeight);
   /**** WICHTIG: Sketch soll stoppen, sobald Fenster zu klein wird / neu starten, wenn wieder richtige
    Größe erreicht ist****/
}

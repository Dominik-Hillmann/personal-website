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
         if (this.current < this.min || this.current > this.max)
            this.down = !this.down;
         this.curFrame = frame;
      }
      return this.current;
   }
}


class Wave {

   constructor() {
      
   }

   sin() {

   }

   drawWave() {

   }
}

function setup() {

}

function draw() {

}

function resize() {

}

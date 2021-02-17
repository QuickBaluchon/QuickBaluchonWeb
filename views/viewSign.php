<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canvas</title>
    <style>
      body {
        margin: 0;
        padding: 0;
        overflow: hidden;
        background: #ccc;
      }

      .toolbar {
        width: 150px;
        height: 75px;
        background: #ccc;
        padding: 5px;
      }

      input[type="color"], button {
        width: 90%;
        margin: 0 auto;
        display: block;
      }

      input[type="range"] {
        width: 70%;
      }

       span {
         position: relative;
         bottom: 5px;
       }
    </style>
  </head>
  <body>
    <div class="toolbar">
      <input type="color" aria-label="select pen color">
      <input type="range" min="2" max="50" value="30" aria-label="select pen size"><span class="output">30</span>
      <button>Clear canvas</button>
      <button type="button" onclick="url()">save Image</button>
    </div>


    <canvas class="myCanvas">
      <p>Add suitable fallback here.</p>
    </canvas>
    <img id='image'>
    <script>
      const image = document.getElementById('image');
      const canvas = document.querySelector('.myCanvas');
      const width = canvas.width = 250;
      const height = canvas.height = 250;
      const ctx = canvas.getContext('2d');

      ctx.fillStyle = 'rgb(255,255,255)';
      ctx.fillRect(0,0,width,height);

      const colorPicker = document.querySelector('input[type="color"]');
      const sizePicker = document.querySelector('input[type="range"]');
      const clearBtn = document.querySelector('button');

      // covert degrees to radians
      function degToRad(degrees) {
        return degrees * Math.PI / 180;
      };

      // store mouse pointer coordinates, and whether the button is pressed
      let curX;
      let curY;
      let pressed = true;

      // update mouse pointer coordinates
      document.addEventListener("touchmove", move, false);
      document.addEventListener("touchstart", start, false);

      function move(e) {
        let change = e.changedTouches
        curX = change[0].pageX;
        curY = change[0].pageY;
        console.log(curX);
      }

      function start() {
        pressed = true;
      }

      canvas.onmouseup = function() {
        pressed = false;
      }

      clearBtn.onclick = function() {
        ctx.fillStyle = 'rgb(255,255,255)';
        ctx.fillRect(0,0,width,height);
      }

      function draw() {

        if(pressed) {
          ctx.fillStyle = colorPicker.value;
          ctx.beginPath();
          ctx.arc(curX, curY-85, sizePicker.value, degToRad(0), degToRad(360), false);
          ctx.fill();

        }

        requestAnimationFrame(draw)
      }

      draw();

      function url() {
        let reader  = new FileReader();
        let dataUrl = canvas.toDataURL("image/jpeg");
        image.src = dataUrl;

      }

    </script>
  </body>
</html>

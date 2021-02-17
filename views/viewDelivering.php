<!DOCTYPE html>
<html lang="fr">
  <!-- HEAD -->
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link rel="stylesheet" href="../bootstrap-4.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/delivering.css">

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <title>QuickBaluchon</title>
  </head>

  <!-- BODY -->
  <body class="bg-dark">
    <main>
      <div id="container" class="container-sm d-flex justify-content-center">
        <div class="col-8 d-flex flex-column justify-content-center">

            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-danger btn-sm btn-block">Clear canvas</button>
                </div>
                <div class="card-body">
                    <canvas class="myCanvas">
                      <p>Add suitable fallback here.</p>
                    </canvas>
                    <img id='image'>
                    <script class="mb-1">
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
                </div>

                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-lg btn-block">Livré</button>
                </div>
            </div>


          <hr>
          <button type="button" class="btn btn-warning btn-lg btn-block">Absent</button>
        </div>
      </div>
    </main>
  </body>
</html>

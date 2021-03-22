<?php
if( isset($_GET['url']) && strlen($_GET['url']) > 0) {
  $url = explode( '/', filter_var($_GET['url'],
  FILTER_SANITIZE_URL) );
  $id = intval($url[1]) ;
}

$this->_css = ['header', 'home', 'main', 'delivering'];
$this->_js = ['main', 'package'] ;
$this->_header = ROOT . '/templates/Front/header.php';

extract($this->_content) ;
?>

<div id="container" class="container-sm d-flex justify-content-center mt-3">
    <div class="col-8 d-flex flex-column justify-content-center" id="jumbotron">

        <div class="card">
            <div class="card-header">
                <h2><?= $TitleDelivering ?></h2>
                <button type="button" class="btn btn-danger btn-sm btn-block"><?= $ButtonClear ?></button>
            </div>
            <div class="card-body" id="canvas">
                <canvas class="myCanvas" id="signature">
                  <p><?= $TextFallback ?></p>
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
                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="deliver(<?= $id ; ?>)"><?= $ButtonDelivered ?></button>
            </div>
        </div>


        <hr>
        <button type="button" class="btn btn-warning btn-lg btn-block" onclick="absent(<?= $id ; ?>)"><?= $ButtonAbsent ?></button>
        <hr>
        <hr>
        <div class="card">
            <div class="card-header">
                <h2><?= $TitleStop ?></h2>
            </div>
            <div class="card-body">
                <p><?= $WarningStop ?></p>
                <button type="button" class="btn btn-danger btn-lg btn-block mt-3" onclick="stopDeliveries(<?= $id ; ?>)"><?= $ButtonStop ?></button>
            </div>
        </div>
    </div>
</div>

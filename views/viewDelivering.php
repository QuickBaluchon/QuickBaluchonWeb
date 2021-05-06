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
                  let curX, curY;
                  let x, y;
                  let lastX, lastY;
                  let pressed = false;

                  // update mouse pointer coordinates
                  document.addEventListener("mousemove", move);
                  document.addEventListener("mousedown", function(e){pressed = true});
                  document.addEventListener("mouseup", up);

                  function move(e) {
                    curX = e.x;
                    curY = e.y;
                  }

                  function up(e) {
                      pressed = false
                      lastY = null
                      lastX = null
                  }

                  clearBtn.onclick = function() {
                    ctx.fillStyle = 'rgb(255,255,255)';
                    ctx.fillRect(0,0,width,height);
                  }

                  function draw() {
                      //console.log(pressed)
                      if(pressed) {
                          offset = canvas.getBoundingClientRect();
                          lastX = x
                          lastY = y
                          x = curX - offset.left
                          y = curY - offset.top

                          ctx.fillStyle = 'black';
                          ctx.lineWidth = 3;

                          ctx.beginPath();
                          ctx.moveTo(lastX, lastY);
                          ctx.lineTo(x, y);
                          ctx.stroke();
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
            <form action="/OneSignal/ControllerOneSignal.php" method="post" onsubmit="return false" id="formOneSignalDeliver">
                <input type="hidden" name="app_id" value="aae49060-185a-416a-ac6a-2564915dd8c6">
                <input type="hidden" id="user" name="send">
                <input type="hidden" name="message" value="Vous avez bien livré le colis">
                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="deliver(<?= $id ; ?>)"><?= $ButtonDelivered ?></button>
                </div>
            </form>

        </div>

        <hr>
        <form action="/OneSignal/ControllerOneSignal.php" method="post" onsubmit="return false" id="formOneSignalAbsent">
            <input type="hidden" name="app_id" value="aae49060-185a-416a-ac6a-2564915dd8c6">
            <input type="hidden" id="userAbsent" name="send">
            <button type="button" class="btn btn-warning btn-lg btn-block" onclick="absent(<?= $id ; ?>)"><?= $ButtonAbsent ?></button>
        </form>
    </div>
</div>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "aae49060-185a-416a-ac6a-2564915dd8c6",
    });
  });
</script>
<script type="text/javascript" src="/media/script/oneSignal.js"></script>

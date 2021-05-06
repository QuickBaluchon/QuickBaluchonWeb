    <form action="/OneSignal/ControllerOneSignal.php" method="post">
            <input type="hidden" name="app_id" value="aae49060-185a-416a-ac6a-2564915dd8c6">
            <input type="hidden" id="user" name="send">
            <input type="submit" value="send">
        </form>

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

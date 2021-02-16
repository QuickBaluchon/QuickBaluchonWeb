let userid = document.getElementById('user')

OneSignal.push(function() {
isEnabled = 1;
  /* These examples are all valid */
  var isPushSupported = OneSignal.isPushNotificationsSupported();

  if (isPushSupported) {
    console.log("supported");
 OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        if (isEnabled){

          console.log("Push notifications are enabled!");
          OneSignal.getUserId(function(userId) {
              console.log("OneSignal User ID:", userId);
              userid.value = userId;
              // (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316
            });
        } else {

          console.log("Push notifications are not enabled yet.");

              OneSignal.showSlidedownPrompt();

        }
    )};


  } else {
    console.log("not supported");
  }
});

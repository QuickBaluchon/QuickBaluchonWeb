let userid = document.getElementById('user')

OneSignal.push(function() {

  /* These examples are all valid */
  var isPushSupported = OneSignal.isPushNotificationsSupported();

  if (isPushSupported) {
        OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        if (isEnabled){
          OneSignal.getUserId(function(userId) {
              console.log("OneSignal User ID:", userId);
              userid.value = userId;
            });
        } else {
          console.log("Push notifications are not enabled yet.");
              OneSignal.showSlidedownPrompt();
        }
    });
  } else {
    console.log("not supported");
  }
});

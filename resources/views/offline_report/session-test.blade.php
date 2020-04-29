<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>jquery-idleTimeout - Example Page</title>

  <link type="text/css" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css"  />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js" type="text/javascript"></script>

  <script type="text/javascript" src="{{ asset('js/reports_js/assets/jquery-idleTimeout/store.legacy.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('js/reports_js/assets/jquery-idleTimeout/jquery-idleTimeout.min.js') }}"></script>

  <script type="text/javascript" charset="utf-8">
   store.set('user', { name:'Marcus' });
   store.get('user').name == 'Marcus';
   //alert("hi");
    $(document).ready(function (){
      $(document).idleTimeout({
      redirectUrl: 'https://logout.php',      // redirect to this url on logout. Set to "redirectUrl: false" to disable redirect

      // idle settings
      idleTimeLimit: 2,           // 'No activity' time limit in seconds. 1200 = 20 Minutes
      idleCheckHeartbeat: 2,       // Frequency to check for idle timeouts in seconds

      // optional custom callback to perform before logout
      customCallback: false,       // set to false for no customCallback
      // customCallback:    function () {    // define optional custom js function
          // perform custom action before logout
      // },

      // configure which activity events to detect
      // http://www.quirksmode.org/dom/events/
      // https://developer.mozilla.org/en-US/docs/Web/Reference/Events
      activityEvents: 'click keypress scroll wheel mousewheel mousemove', // separate each event with a space

      // warning dialog box configuration
      enableDialog: true,           // set to false for logout without warning dialog
      dialogDisplayLimit: 20,       // 20 seconds for testing. Time to display the warning dialog before logout (and optional callback) in seconds. 180 = 3 Minutes
      dialogTitle: 'Session Expiration Warning', // also displays on browser title bar
      dialogText: 'Because you have been inactive, your session is about to expire.',
      dialogTimeRemaining: 'Time remaining',
      dialogStayLoggedInButton: 'Stay Logged In',
      dialogLogOutNowButton: 'Log Out Now',

      // error message if https://github.com/marcuswestin/store.js not enabled
      errorAlertMessage: 'Please disable "Private Mode", or upgrade to a modern browser. Or perhaps a dependent file missing. Please see: https://github.com/marcuswestin/store.js',

      // server-side session keep-alive timer
      sessionKeepAliveTimer: 600,   // ping the server at this interval in seconds. 600 = 10 Minutes. Set to false to disable pings
      sessionKeepAliveUrl: window.location.href // set URL to ping - does not apply if sessionKeepAliveTimer: false
      });
    });
  </script>

</head>
<body>

  <h1>jquery-idleTimeout Example Page</h1>

  <p>This is an example of how to set up a webpage to use the jquery-idleTimeout.</p>

  <p>Use the 'Logout' button below as your site's logout button to log out all open windows & tabs on your site.</p>

  <input type="button" value="Your Site's Logout Button" onclick="$.fn.idleTimeout().logout();" title="This button will logout ALL open 'same domain' Windows & Tabs" />

  <p>You must change the 'redirectUrl' configuration variable to point to your site's logout page!</p>

  <p>All other configuration values can be run at their default settings.</p>

</body>
</html>



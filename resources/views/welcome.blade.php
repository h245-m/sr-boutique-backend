<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
  <script>

    Pusher.logToConsole = true;

    var pusher = new Pusher('ce36596acdd6873dc8bf', {
      cluster: 'eu',
      authEndpoint: '/broadcasting/auth',
      auth: {
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
          }
      }
    });

    var channel = pusher.subscribe('private-test');
    channel.bind('test', function(data) {
      alert("Gamed");
    });

    // window.onload=function(){
    //     window.Echo.private('test').listen('test', function(data) {
    //       alert("Gamed");
    //     });
    // }

  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
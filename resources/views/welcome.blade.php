<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('ce36596acdd6873dc8bf', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('private-chat.8');
    channel.bind('chatMessage', function(data) {
      // alert(JSON.stringify(data));
    });

    var groupChannel = pusher.subscribe('chatRoom');
    groupChannel.bind('chatRoomCreated', function(data) {
      alert(JSON.stringify(data));
    })

  </script>
</head>
<body>
  <h1>Pusher gg</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
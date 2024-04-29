<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>

    Pusher.logToConsole = true;

    // var pusher = new Pusher('ce36596acdd6873dc8bf', {
    //   cluster: 'eu',
    //   authEndpoint: '/broadcasting/auth',
    //   auth: {
    //       headers: {
    //           'X-CSRF-TOKEN': '{{ csrf_token() }}',
    //       }
    //   }
    // });

    // var channel = pusher.subscribe('private-chat.{{ Auth::user()->id }}' );
    // channel.bind('chatMessage', function(data) {
    //   console.log(JSON.stringify(data));
    // });



    async function fetchAuth(socketId, channelName) {
            try {
                const myHeaders = new Headers();
                myHeaders.append("Accept", "application/json");
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Authorization", "Bearer 4|w0HqbierMJ1PdUphEgSft0dAhKU0Iy0VWRifA5knaef929d9");
                const raw = JSON.stringify({
                    "socket_id": socketId,
                    "channel_name": channelName
                });

                const requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };

                const response = await fetch("http://localhost:8000/chatify/api/chat/auth", requestOptions);
                const result = await response.json();

                return result
            } catch (error) {
                console.error('error', error);
            }
        }




        const pusher = new Pusher("ce36596acdd6873dc8bf", {
            cluster: 'eu',
            channelAuthorization: {
                customHandler : async ({socketId, channelName}, callback) => {
                    const data = await fetchAuth(socketId, channelName);
                    callback(null, {
                        "auth": data["auth"],
                        "channel_data": data["channel_data"]
                    })
                },
            }
        });

          var channel = pusher.subscribe('private-chatify.{{ Auth::user()->id }}' );
          channel.bind('client-seen', function(data) {
            console.log(JSON.stringify(data));
          });
          channel.bind('client-contactItem', function(data) {
            console.log(JSON.stringify(data));
          });


  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
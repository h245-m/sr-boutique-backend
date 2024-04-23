@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <script src="js/app.js"></script>

    <script>
        const channel = window.Echo.channel('chat');

        channel.listen('chatMessage', (e) => {
            console.log(e);
        });

    </script>


</div>
@endsection

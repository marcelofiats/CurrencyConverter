<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Currency Converter - Login</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            body {
                background-color: rgb(190, 190, 190);
            }
            #box {
                height: 600px;
                margin-top: 8%;
                background-color: rgb(147, 147, 218);
                border-radius: 60px 10px;
            }
            #box_header{
                margin-top: 15%;
                margin-bottom: 5%;
            }
        </style>
    </head>
    <body>
        <div class="row d-flex justify-content-center">
            <div id="box" class="col-md-4 col-sm-10 p-5">
                @auth
                    <div class="text-center mt-5">
                        <a href="{{ url('/home') }}" class="btn btn-info btn-lg"> Home </a>
                    </div>
                @else
                    <form id="loginForm" action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="row">
                            <div id="box_header" class="col-md-12 text-center">
                                <h2>Login</h2>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="email" id="email" placeholder="E-mail">
                                <div id="errorEmail" class="text-danger"></div>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-5">
                                <input type="password" class="form-control" name="password" id="password" placeholder="password">
                                <div id="errorPassword" class="text-danger"></div>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 text-center mt-4">
                                <button class="btn btn-success px-4">Enter</button>
                            </div>
                        </div>
                    </form>
                @endauth

            </div>
        </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $('#loginForm').submit(function(event){
            event.preventDefault();
            var success = true;
            $('#errorEmail').html('');
            $('#errorpassword').html('');

            if ($('#email').val() == '') {
                success = false;
                $('#errorEmail').html('Digite o email');
            }
            if ($('#email').val().indexOf('@') < 0 ) {
                success = false;
                $('#errorEmail').html('Digite um email valído');
            }
            if ($('#password').val() == '') {
                success = false;
                $('#password').html('Digite a senha');
            }
            if (success) {
                event.target.submit();
            }
        });
    </script>
    </body>
</html>

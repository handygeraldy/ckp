<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        html,
        body {
            height: 100%;
        }
    </style>
    <script src="https://kit.fontawesome.com/34dfa7fd49.js" crossorigin="anonymous"></script>
</head>

<body>
    @include('sweetalert::alert')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            
            <div class="col-10 col-md-8 col-lg-6">
                {{-- <img class="rounded mx-auto d-block img-fluid m-3" src="{{ asset('img/logo.png') }}"> --}}
                <div class="card">
                    <div class="card-header">
                        <h3>Sign In</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('login.post') }}" method="post">
                            @csrf
                            <div class="input-group form-group">
                                <input type="email" name="email" placeholder="email" class="form-control @error('email') is-invalid @enderror" value="" required autofocus>
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{  $message }}    
                                    </div>    
                                @enderror
                            </div>
                            <div class="input-group form-group">
                                <input type="password" name="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{  $message }}    
                                    </div>    
                                @enderror
                            </div>
                            {{-- <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                                <label class="custom-control-label" for="remember">Remember Me</label>
                            </div> --}}
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary float-right" value="Sign In">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
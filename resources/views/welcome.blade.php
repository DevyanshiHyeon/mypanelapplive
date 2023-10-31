@include('layout.links')
<body class="bg-gray-200">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background-image: url({{url('img/login_pic.jpg')}})"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    @if ($errors->any())
@foreach ($errors->all() as $error)
<li class="text-danger">{{ $error }}</li>
@endforeach
@endif
                                    <form class="user" action="{{url('/login')}}" method="POST">@csrf

                                        @if (session()->has('OTP'))
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="otp" placeholder="OTP">
                                        </div>
                                        @else
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                name="email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." value="{{old('email')}}">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" placeholder="Password">
                                        </div>
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            @if (session()->has('OTP')) Login @else Send OTP @endif
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    @include('layout.scripts')
</body>

</html>

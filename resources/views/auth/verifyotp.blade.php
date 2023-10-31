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

                                @if(session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                <div class="alert alert-success" id="resend-alert"></div>
                                    <form class="user" action="{{url('/verifyOtp')}}" method="POST" id="otp_form">@csrf

                                        @if (session()->has('success'))
                                        <div class="text-success">{{ session()->get('success') }}</div>
                                        @endif
                                        @if (session()->has('error'))
                                        <div class="text-danger">{{ session()->get('error') }}</div>
                                        @endif
                                        {{-- <input type="hidden" name="password" value="{{ session()->get('password') }}"> --}}
                                        @isset($data['password'])
                                        <input type="hidden" name="password" value="{{$data['password']}}">
                                        @endisset
                                        @isset($data['email'])
                                        <input type="hidden" name="email" value="{{$data['email']}}">
                                        @endisset
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="otp" placeholder="OTP">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                    </form>
                                    {{-- <div class="container"> --}}
                                        <div class="d-flex justify-content-between">
                                            <div class="col-md-8">
                                                <a href="{{url('/')}}"><p>Back</p></a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="javascript:resendotp()">Resend OTP</a>
                                                {{-- <a href="javascript:location.reload()">Resend OTP</a> --}}
                                            </div>
                                        </div>
                                    {{-- </div> --}}
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
<script>
    baseUrl = window.location.origin;
    $('#resend-alert').hide();
    function resendotp() {
        $.ajax({
            url: baseUrl+'/resend-otp',
            data: $('#otp_form').serialize(),
            success: function (res) {
                $('#resend-alert').text(res.message).show();
                console.log(res);
            },
            error: function (res) {
                console.log(res);
            },
        })
    }
</script>
</html>

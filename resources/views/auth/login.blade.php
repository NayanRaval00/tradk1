<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Login')</title>
    <!-- Fonts and Global Styles -->

    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico')}}" />


    <link href="{{asset('/new_templete/dist/assets/css/styles.css')}}" rel="stylesheet" type="text/css" />
</head>

<body id="kt_body" class="">
    <div id="main-wrapper">
        <div class="position-relative overflow-hidden auth-bg min-vh-100 w-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100 my-5 my-xl-0">
                    <div class="col-md-9 d-flex flex-column justify-content-center">
                        <div class="card mb-0 bg-body auth-login m-auto w-100">
                            <div class="row gx-0">
                                <!-- ------------------------------------------------- -->
                                <!-- Part 1 -->
                                <!-- ------------------------------------------------- -->
                                <div class="col-xl-6 border-end">
                                    <div class="row justify-content-center py-4">
                                        <div class="col-lg-11">
                                            <div class="card-body">
                                                <a href="" class="text-nowrap logo-img d-block mb-4 w-100">
                                                    <img src="{{asset('assets/media/logos/shiparcel_logo.png')}}" class="dark-logo" alt="Logo-Dark" />
                                                </a>
                                                <h2 class="lh-base mb-4">Let's get you signed in</h2>
                                                <div class="row">
                                                    <div class="col-6 mb-2 mb-sm-0">
                                                        <a class="btn btn-white shadow-sm text-dark link-primary border fw-semibold d-flex align-items-center justify-content-center rounded-1 py-6" href="javascript:void(0)" role="button">
                                                            <img src="{{asset('assets/media/logos/facebook-icon.svg')}}" alt="matdash-img" class="img-fluid me-2" width="18" height="18">
                                                            <span class="d-none d-xxl-inline-flex"> Sign in with </span>&nbsp; Facebook
                                                        </a>
                                                    </div>
                                                    <div class="col-6">
                                                        <a class="btn btn-white shadow-sm text-dark link-primary border fw-semibold d-flex align-items-center justify-content-center rounded-1 py-6" href="javascript:void(0)" role="button">
                                                            <img src="{{asset('assets/media/logos/google-icon.svg')}}" alt="matdash-img" class="img-fluid me-2" width="18" height="18">
                                                            <span class="d-none d-xxl-inline-flex"> Sign in with </span>&nbsp; Google
                                                        </a>

                                                    </div>
                                                </div>
                                                <div class="position-relative text-center my-4">
                                                    <p class="mb-0 fs-12 px-3 d-inline-block bg-body z-index-5 position-relative">Or sign in with
                                                        email
                                                    </p>
                                                    <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                                                </div>
                                                <form method="POST" action="{{ route('custom.login.submit') }}">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Email Address</label>
                                                        <input name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter your email" aria-describedby="emailHelp">
                                                        @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                                            <a class="text-primary link-dark fs-2" href="">Forgot
                                                                Password ?</a>
                                                        </div>
                                                        <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter your password">
                                                        @error('password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                                                            <label class="form-check-label text-dark" for="flexCheckChecked">
                                                                Keep me logged in
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="submit" id="" class="btn btn-dark w-100 py-8 mb-4 rounded-1">Sign In</button>

                                                    <div class="d-flex align-items-center">
                                                        <!-- <p class="fs-12 mb-0 fw-medium">Don’t have an account yet?</p> -->
                                                        <!-- <a class="text-primary fw-bolder ms-2" href="../main/authentication-register2.html">Sign Up Now</a> -->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- ------------------------------------------------- -->
                                <!-- Part 2 -->
                                <!-- ------------------------------------------------- -->
                                <div class="col-xl-6 d-none d-xl-block">
                                    <div class="row justify-content-center align-items-start h-100">
                                        <div class="col-lg-9">
                                            <div id="auth-login" class="carousel slide auth-carousel mt-5 pt-4" data-bs-ride="carousel">
                                                <div class="carousel-indicators">
                                                    <button type="button" data-bs-target="#auth-login" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                    <button type="button" data-bs-target="#auth-login" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                    <button type="button" data-bs-target="#auth-login" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                </div>
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                                                            <img src="{{asset('assets/media/logos/shiparcel_logo.png')}}" alt="login-side-img" width="300" class="img-fluid" />
                                                            <h4 class="mb-0">Feature Rich 3D Charts</h4>
                                                            <p class="fs-12 mb-0">Donec justo tortor, malesuada vitae faucibus ac, tristique sit amet
                                                                massa.
                                                                Aliquam dignissim nec felis quis imperdiet.</p>
                                                            <a href="javascript:void(0)" class="btn btn-primary rounded-1">Learn More</a>
                                                        </div>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                                                            <img src="{{asset('assets/media/logos/shiparcel_logo.png')}}" alt="login-side-img" width="300" class="img-fluid" />
                                                            <h4 class="mb-0">Feature Rich 2D Charts</h4>
                                                            <p class="fs-12 mb-0">Donec justo tortor, malesuada vitae faucibus ac, tristique sit amet
                                                                massa.
                                                                Aliquam dignissim nec felis quis imperdiet.</p>
                                                            <a href="javascript:void(0)" class="btn btn-primary rounded-1">Learn More</a>
                                                        </div>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                                                            <img src="{{asset('assets/media/logos/shiparcel_logo.png')}}" alt="login-side-img" width="300" class="img-fluid" />
                                                            <h4 class="mb-0">Feature Rich 1D Charts</h4>
                                                            <p class="fs-12 mb-0">Donec justo tortor, malesuada vitae faucibus ac, tristique sit amet
                                                                massa.
                                                                Aliquam dignissim nec felis quis imperdiet.</p>
                                                            <a href="javascript:void(0)" class="btn btn-primary rounded-1">Learn More</a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>
    <script src="{{asset('/new_templete/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('/new_templete/assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('/new_templete/assets/js/theme/app.init.js')}}"></script>
    <script src="{{asset('/new_templete/assets/js/theme/theme.js')}}"></script>
    <script src="{{asset('/new_templete/assets/js/theme/app.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>



    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - LaundryKuy</title>
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="{{ url('/') }}" class="fs-2 fw-bold text-primary">
                            <i class="bi bi-basket-fill"></i> LaundryKuy
                        </a>
                    </div>
                    <h1 class="auth-title">Daftar.</h1>
                    <p class="auth-subtitle mb-5">Bergabunglah untuk menikmati layanan antar-jemput laundry.</p>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="name" class="form-control form-control-xl @error('name') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" name="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="Password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password_confirmation" class="form-control form-control-xl" placeholder="Ulangi Password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Buat Akun</button>
                    </form>

                    <div class="text-center mt-5 text-lg fs-4">
                        <p class='text-gray-600'>Sudah punya akun? <a href="{{ route('login') }}" class="font-bold">Masuk disini</a>.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    </div>
            </div>
        </div>

    </div>
</body>

</html>
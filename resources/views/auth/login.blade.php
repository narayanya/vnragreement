<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Agreement Management System - Login</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #ffffff;
            color: #15345f;
            overflow-x: hidden;
        }

        /* =========================
           MAIN BACKGROUND
        ========================= */

        .login-page {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 78% 30%,
                    rgba(224, 237, 255, .85) 0,
                    rgba(224, 237, 255, .5) 18%,
                    transparent 38%),
                linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .circle-one {
            width: 480px;
            height: 480px;
            top: -250px;
            right: -50px;
            background: rgba(207, 226, 252, .42);
        }

        .circle-two {
            width: 260px;
            height: 260px;
            bottom: -150px;
            right: -70px;
            background: rgba(221, 235, 255, .65);
        }

        .circle-three {
            width: 140px;
            height: 140px;
            top: 120px;
            right: 130px;
            background: rgba(192, 216, 252, .5);
        }

        /* =========================
           DECORATIVE DOTS
        ========================= */

        .dots {
            position: absolute;
            width: 130px;
            height: 100px;
            opacity: .45;
            background-image: radial-gradient(#b7d2f7 2px, transparent 2px);
            background-size: 16px 16px;
        }

        .dots-top {
            top: 0;
            left: 38%;
        }

        .dots-right {
            right: 0;
            top: 35%;
        }

        .dots-bottom {
            right: 42%;
            bottom: 50px;
        }

        /* =========================
           CONTENT
        ========================= */

        .login-container {
            min-height: 100vh;
            position: relative;
            z-index: 5;
            padding: 35px 5%;
        }

        /* =========================
           LOGO
        ========================= */

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 65px;
        }

        .brand-icon {
            width: 55px;
            height: 65px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon i {
            font-size: 55px;
            color: #2879ed;
        }

        .brand-icon .pen {
            position: absolute;
            right: -2px;
            bottom: 4px;
            font-size: 24px;
            color: #21ad9c;
            transform: rotate(-10deg);
        }

        .brand-title {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: .5px;
            color: #183967;
            line-height: 1;
        }

        .brand-subtitle {
            font-size: 17px;
            letter-spacing: 1px;
            color: #637799;
            margin-top: 7px;
        }

        /* =========================
           LEFT SECTION
        ========================= */

        .left-section {
            padding-top: 0;
            padding-left: 15px;
        }

        .secure-label {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border-radius: 14px;
            background: linear-gradient(90deg, #e9f9f6, #f5f8fc);
            color: #536a91;
            font-size: 15px;
            margin-bottom: 25px;
        }

        .secure-label i {
            color: #25ae91;
            font-size: 21px;
        }

        .main-title {
            font-size: 49px;
            line-height: 1.25;
            font-weight: 700;
            color: #15355f;
            max-width: 600px;
            margin-bottom: 25px;
        }

        .main-title span {
            color: #20b89c;
        }

        .title-line {
            width: 58px;
            height: 3px;
            background: #2379ed;
            margin: 25px 0;
        }

        .description {
            max-width: 415px;
            color: #536a90;
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        /* =========================
           FEATURES
        ========================= */

        .feature {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 17px;
        }

        .feature-icon {
            width: 54px;
            height: 54px;
            min-width: 54px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .feature:nth-child(1) .feature-icon {
            background: #e7f0ff;
            color: #2675e7;
        }

        .feature:nth-child(2) .feature-icon {
            background: #e7f8f4;
            color: #19a88d;
        }

        .feature:nth-child(3) .feature-icon {
            background: #efedff;
            color: #8071e7;
        }

        .feature:nth-child(4) .feature-icon {
            background: #fff4e5;
            color: #f29b26;
        }

        .feature-title {
            font-size: 16px;
            font-weight: 600;
            color: #163a70;
            margin-bottom: 4px;
        }

        .feature-text {
            font-size: 14px;
            color: #63779a;
        }

        /* =========================
           AGREEMENT ILLUSTRATION
        ========================= */

        .agreement-illustration {
            position: absolute;
            left: 45%;
            top: 53%;
            width: 390px;
            height: 390px;
            transform: translate(-50%, -50%) rotate(-4deg);
            z-index: 1;
        }

        .paper-shadow {
            position: absolute;
            width: 270px;
            height: 350px;
            left: 65px;
            top: 30px;
            border-radius: 12px;
            background: #b9d8ff;
            transform: rotate(14deg);
            box-shadow: 0 20px 35px rgba(60, 125, 210, .18);
        }

        .paper-back {
            position: absolute;
            width: 270px;
            height: 350px;
            left: 45px;
            top: 22px;
            border-radius: 10px;
            background: #edf5ff;
            transform: rotate(9deg);
        }

        .paper {
            position: absolute;
            width: 270px;
            height: 350px;
            left: 28px;
            top: 18px;
            padding: 32px 25px;
            border-radius: 10px;
            background: white;
            transform: rotate(9deg);
            box-shadow: 0 20px 35px rgba(43, 89, 147, .18);
        }

        .paper h4 {
            font-size: 18px;
            color: #2468c9;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .paper-line {
            height: 6px;
            background: #e4e9f1;
            border-radius: 10px;
            margin-bottom: 13px;
        }

        .paper-line.short {
            width: 60%;
        }

        .paper-line.medium {
            width: 78%;
        }

        .signature {
            margin-top: 30px;
            font-size: 34px;
            color: #7188a8;
            font-family: cursive;
        }

        .check-circle {
            position: absolute;
            right: 30px;
            bottom: 35px;
            width: 48px;
            height: 48px;
            border: 3px solid #3984ed;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3984ed;
            font-size: 24px;
        }

        .pen {
            position: absolute;
            width: 190px;
            height: 18px;
            right: 10px;
            bottom: 72px;
            border-radius: 12px;
            background: linear-gradient(90deg, #222936, #080c13);
            transform: rotate(-43deg);
            z-index: 5;
            box-shadow: 0 7px 10px rgba(0, 0, 0, .22);
        }

        .pen:before {
            content: "";
            position: absolute;
            right: -23px;
            top: 0;
            border-top: 9px solid transparent;
            border-bottom: 9px solid transparent;
            border-left: 28px solid #d79b37;
        }

        .pen:after {
            content: "";
            position: absolute;
            right: 5px;
            top: -4px;
            width: 32px;
            height: 26px;
            border-radius: 5px;
            background: linear-gradient(90deg, #d59b35, #f0c56a);
        }

        /* Floating icons */

        .float-icon {
            position: absolute;
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 10px 25px rgba(58, 105, 165, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 27px;
            z-index: 10;
        }

        .shield-icon {
            right: 45px;
            top: 15px;
            color: #25b493;
        }

        .bell-icon {
            right: -15px;
            top: 205px;
            color: #f5a52e;
        }

        .folder-icon {
            right: 25px;
            bottom: 10px;
            color: #287ce9;
        }

        /* =========================
           LOGIN CARD
        ========================= */

        .login-wrapper {
            display: flex;
            justify-content: flex-end;
            padding-right: 1%;
        }

        .login-card {
            width: 555px;
            min-height: 600px;
            padding: 30px 40px;
            background: rgba(255, 255, 255, .96);
            border-radius: 28px;
            box-shadow:
                0 20px 55px rgba(44, 91, 150, .14),
                0 2px 8px rgba(44, 91, 150, .06);
        }

        .user-circle {
            width: 104px;
            height: 104px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #edf5ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-circle i {
            font-size: 53px;
            color: #2878e9;
        }

        .welcome-title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: #17365f;
            margin-bottom: 5px;
        }

        .welcome-text {
            text-align: center;
            font-size: 18px;
            color: #63779a;
            margin-bottom: 35px;
        }

        .form-label {
            color: #122f56;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 11px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 27px;
        }

        .input-wrapper > i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aaac1;
            font-size: 23px;
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            height: 45px;
            border: 1px solid #cad6e7;
            border-radius: 12px;
            padding: 0 55px;
            font-size: 16px;
            color: #19385f;
            outline: none;
            transition: .25s;
            background: #fff;
        }

        .form-control-custom::placeholder {
            color: #8394b0;
        }

        .form-control-custom:focus {
            border-color: #2879ec;
            box-shadow: 0 0 0 4px rgba(40, 121, 236, .08);
        }

        .password-eye {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ba9bf;
            font-size: 22px;
            cursor: pointer;
        }

        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -4px;
            margin-bottom: 28px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #617496;
            font-size: 15px;
        }

        .remember input {
            width: 22px;
            height: 22px;
            accent-color: #2879ed;
        }

        .forgot-password {
            color: #196bea;
            text-decoration: none;
            font-size: 15px;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            height: 55px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(90deg, #2879eb, #3177e8);
            color: white;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0 12px 22px rgba(43, 116, 228, .22);
            transition: .25s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(43, 116, 228, .3);
        }

        .btn-login i {
            font-size: 23px;
            margin-right: 10px;
        }

        /* =========================
           OR DIVIDER
        ========================= */

        .or-divider {
            display: flex;
            align-items: center;
            gap: 25px;
            margin: 30px 0;
            color: #70819b;
        }

        .or-divider:before,
        .or-divider:after {
            content: "";
            height: 1px;
            background: #d8e0eb;
            flex: 1;
        }

        /* =========================
           SSO
        ========================= */

        .btn-sso {
            width: 100%;
            height: 61px;
            border-radius: 12px;
            border: 1px solid #2879ed;
            background: white;
            color: #185bc0;
            font-size: 17px;
            font-weight: 600;
            transition: .25s;
        }

        .btn-sso:hover {
            background: #f3f8ff;
        }

        .btn-sso i {
            color: #8292aa;
            font-size: 23px;
            margin-right: 10px;
        }

        .security-text {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 34px;
            color: #7587a5;
            font-size: 15px;
        }

        .security-text i {
            color: #21ae93;
            font-size: 21px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1400px) {

            .main-title {
                font-size: 42px;
            }

            .agreement-illustration {
                left: 45%;
                transform: translate(-50%, -50%) scale(.82) rotate(-4deg);
            }

            .login-card {
                width: 500px;
                min-height: 600px;
            }
        }

        @media (max-width: 1100px) {

            .left-section {
                padding-left: 0;
            }

            .agreement-illustration {
                display: none;
            }

            .login-wrapper {
                justify-content: center;
            }

            .login-card {
                width: 500px;
            }

            .main-title {
                font-size: 40px;
            }
        }

        @media (max-width: 767px) {

            .login-container {
                padding: 25px 20px;
            }

            .brand {
                margin-bottom: 35px;
            }

            .brand-title {
                font-size: 24px;
            }

            .brand-subtitle {
                font-size: 13px;
            }

            .left-section {
                margin-bottom: 40px;
            }

            .main-title {
                font-size: 34px;
            }

            .description {
                font-size: 15px;
            }

            .feature {
                margin-bottom: 14px;
            }

            .login-card {
                width: 100%;
                min-height: auto;
                padding: 35px 25px;
                border-radius: 22px;
            }

            .welcome-title {
                font-size: 27px;
            }

            .welcome-text {
                font-size: 16px;
            }

            .login-options {
                gap: 10px;
            }

            .forgot-password {
                font-size: 14px;
            }
        }

        @media (max-width: 400px) {

            .login-options {
                flex-direction: column;
                align-items: flex-start;
            }

            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>

<div class="login-page">

    <!-- Background decoration -->
    <div class="bg-circle circle-one"></div>
    <div class="bg-circle circle-two"></div>
    <div class="bg-circle circle-three"></div>

    <div class="dots dots-top"></div>
    <div class="dots dots-right"></div>
    <div class="dots dots-bottom"></div>

    <div class="container-fluid login-container">

        <div class="row">

            <!-- =========================
                 LEFT SIDE
            ========================== -->

            <div class="col-lg-7">

                <!-- Brand -->
                <div class="brand">

                    <div class="brand-icon">
                        <i class="ri-file-edit-line"></i>
                        <i class="ri-pencil-fill pen"></i>
                    </div>

                    <div>
                        <div class="brand-title">
                            AGREEMENT
                        </div>

                        <div class="brand-subtitle">
                            MANAGEMENT SYSTEM
                        </div>
                    </div>

                </div>


                <div class="left-section">

                    <!-- Secure badge -->
                    <div class="secure-label">
                        <i class="ri-shield-check-fill"></i>
                        Secure • Reliable • Efficient
                    </div>


                    <!-- Heading -->
                    <h1 class="main-title">
                        Simplify Agreements.<br>
                        Strengthen <span>Trust.</span>
                    </h1>

                    <div class="title-line"></div>


                    <!-- Description -->
                    <p class="description">
                        Create, manage, track, and monitor all your
                        agreements in one centralized platform.
                        Smart workflow. Total control.
                    </p>


                    <!-- Features -->
                    <div class="features">

                        <div class="feature">
                            <div class="feature-icon">
                                <i class="ri-file-text-line"></i>
                            </div>

                            <div>
                                <div class="feature-title">
                                    Centralized Management
                                </div>

                                <div class="feature-text">
                                    All agreements in one place
                                </div>
                            </div>
                        </div>


                        <div class="feature">
                            <div class="feature-icon">
                                <i class="ri-line-chart-line"></i>
                            </div>

                            <div>
                                <div class="feature-title">
                                    Track & Monitor
                                </div>

                                <div class="feature-text">
                                    Real-time status and expiry alerts
                                </div>
                            </div>
                        </div>


                        <div class="feature">
                            <div class="feature-icon">
                                <i class="ri-group-line"></i>
                            </div>

                            <div>
                                <div class="feature-title">
                                    Role-Based Access
                                </div>

                                <div class="feature-text">
                                    Secure access for your team
                                </div>
                            </div>
                        </div>


                        <div class="feature">
                            <div class="feature-icon">
                                <i class="ri-notification-3-line"></i>
                            </div>

                            <div>
                                <div class="feature-title">
                                    Smart Notifications
                                </div>

                                <div class="feature-text">
                                    Never miss an important update
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <!-- Agreement Illustration -->
                <div class="agreement-illustration">

                    <div class="paper-shadow"></div>
                    <div class="paper-back"></div>

                    <div class="paper">

                        <h4>AGREEMENT</h4>

                        <div class="paper-line medium"></div>
                        <div class="paper-line"></div>
                        <div class="paper-line medium"></div>
                        <div class="paper-line"></div>
                        <div class="paper-line short"></div>
                        <div class="paper-line medium"></div>
                        <div class="paper-line"></div>

                        <div class="signature">
                            Sam
                        </div>

                        <div class="check-circle">
                            <i class="ri-check-line"></i>
                        </div>

                    </div>

                    <div class="pen"></div>

                    <div class="float-icon shield-icon">
                        <i class="ri-shield-check-fill"></i>
                    </div>

                    <div class="float-icon bell-icon">
                        <i class="ri-notification-3-fill"></i>
                    </div>

                    <div class="float-icon folder-icon">
                        <i class="ri-folder-3-fill"></i>
                    </div>

                </div>

            </div>


            <!-- =========================
                 RIGHT LOGIN
            ========================== -->

            <div class="col-lg-5 login-wrapper">

                <div class="login-card">

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- User Icon -->
                    <div class="user-circle">
                        <i class="ri-user-fill"></i>
                    </div>

                    <h2 class="welcome-title">
                        Welcome Back!
                    </h2>

                    <p class="welcome-text">
                        Sign in to continue to your account
                    </p>


                    <!-- Laravel Login Form -->
                    <form method="POST" action="{{ route('login') }}">

                        @csrf

                        <!-- Employee Code -->
                        <div class="mb-3">

                            <label class="form-label">
                                Employee Code / Email
                            </label>

                            <div class="input-wrapper">

                                <i class="ri-user-line"></i>

                                <input
                                    type="text"
                                    name="email"
                                    class="form-control-custom"
                                    placeholder="Enter employee code or email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >

                            </div>

                            <x-input-error :messages="$errors->get('email')" />

                        </div>


                        <!-- Password -->
                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <div class="input-wrapper">

                                <i class="ri-lock-line"></i>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control-custom"
                                    placeholder="Enter your password"
                                    required
                                    autocomplete="current-password"
                                >

                                <span
                                    class="password-eye"
                                    onclick="togglePassword()"
                                >
                                    <i class="ri-eye-line" id="eyeIcon"></i>
                                </span>

                            </div>

                            <x-input-error :messages="$errors->get('password')" />

                        </div>


                        <!-- Remember / Forgot -->
                        <div class="login-options">

                            <label class="remember">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                >

                                <span>
                                    Remember me
                                </span>

                            </label>


                            <a
                                href="{{ route('password.request') }}"
                                class="forgot-password"
                            >
                                Forgot Password?
                            </a>

                        </div>


                        <!-- Sign In -->
                        <button
                            type="submit"
                            class="btn-login"
                        >
                            <i class="ri-login-box-line"></i>
                            SIGN IN
                        </button>

                    </form>


                    <!-- OR -->
                    <div class="or-divider" style="display:none;">
                        <span>OR</span>
                    </div>


                    <!-- SSO -->
                    <button style="display:none;"
                        type="button"
                        class="btn-sso"
                    >
                        <i class="ri-building-4-line"></i>
                        SIGN IN WITH SSO
                    </button>


                    <!-- Security -->
                    <div class="security-text">

                        <i class="ri-shield-check-fill"></i>

                        <span>
                            Your data is 100% secure and encrypted
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

    function togglePassword() {

        const password = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        if (password.type === 'password') {

            password.type = 'text';

            icon.classList.remove('ri-eye-line');
            icon.classList.add('ri-eye-off-line');

        } else {

            password.type = 'password';

            icon.classList.remove('ri-eye-off-line');
            icon.classList.add('ri-eye-line');
        }
    }

</script>

</body>
</html>
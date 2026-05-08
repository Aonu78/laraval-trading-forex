@extends(Config::theme() . 'auth.master')

@section('content')
    <form class="sp_account_form mt-4" action="" method="POST">
        @csrf
        <label>{{ __('User email') }}</label>
        <div class="sp_input_icon_field mb-3">
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email"
                placeholder="{{ __('Enter Your Email') }}">
            <i class="las la-envelope"></i>
        </div>
        <label>{{ __('Password') }}</label>
        <div class="sp_input_icon_field password-field mb-3">
            <input type="password" class="form-control" name="password" id="password"
                placeholder="{{ __('Enter Password') }}">
            <i class="las la-lock"></i>
            <button type="button" class="password-toggle" id="togglePassword" aria-label="{{ __('Show password') }}">
                <i class="las la-eye"></i>
            </button>
        </div>

        @if (Config::config()->allow_recaptcha == 1)
            <div class="col-md-12 my-3">
                <script src="https://www.google.com/recaptcha/api.js"></script>
                <div class="g-recaptcha" data-sitekey="{{ Config::config()->recaptcha_key }}" data-callback="verifyCaptcha">
                </div>
                <div id="g-recaptcha-error"></div>
            </div>
        @endif

        <div class="d-flex flex-wrap justify-content-between">
            <div class="form-check sp_site_checkbox mb-2">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label mb-0" for="flexCheckDefault">
                    {{ __('Remember Me') }}
                </label>
            </div>
            <a href="{{ route('user.forgot.password') }}" class="mb-2 sp_site_color">{{ __('Forget Password') }}</a>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn sp_theme_btn w-100">{{ __('Login') }}</button>
        </div>

        <div class="or-text">
            <span>{{ __('Or Login With') }}</span>
        </div>

        <div class="other-login-btns">
            @if (Config::config()->allow_facebook)
                <a class="other-login-btn" href="{{ route('user.facebook.login') }}" id="btn-fblogin">
                    <i class="fab fa-facebook-f"></i>
                    <span>{{ __('Login with Facebook') }}</span>
                </a>
            @endif

            @if (Config::config()->allow_google)
                <a class="other-login-btn" href="{{ route('user.google.login') }}" id="btn-fblogin">
                    <i class="fab fa-google"></i>
                    <span>{{ __('Login with Google') }}</span>
                </a>
            @endif
        </div>

        <p class="mt-4 text-center"> {{ __('Haven\'t an account') }} ? <a href="{{ route('user.register') }}"
                class="sp_site_color">{{ __('Sign Up') }}</a></p>
    </form>
@endsection

@push('external-css')
    <style>
        .password-field .form-control {
            padding-right: 2.8125rem;
        }

        .password-toggle {
            position: absolute;
            top: 0;
            right: 0;
            width: 45px;
            height: 100%;
            border: 0;
            background: transparent;
            color: #c3c1c1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .password-toggle i {
            position: static;
            width: auto;
            font-size: 1.5rem;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const isPassword = password.getAttribute('type') === 'password';
                password.setAttribute('type', isPassword ? 'text' : 'password');
                this.setAttribute('aria-label', isPassword ? '{{ __('Hide password') }}' : '{{ __('Show password') }}');
                this.querySelector('i').classList.toggle('la-eye-slash', isPassword);
            });
        }

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    "<span class='sp_text_danger'>{{ __('Captcha field is required.') }}</span>";
                return false;
            }
            return true;
        }

        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
    </script>
@endpush

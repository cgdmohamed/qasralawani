@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>{{ __('messages.admin_login') }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ __('messages.error_message') }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>{{ __('messages.email') }}</label>
                    <input name="email" type="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.password') }}</label>
                    <input name="password" type="password" class="form-control" required>
                </div>
                <input type="hidden" name="g-recaptcha-response" id="recaptcha">
                <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
                <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {
                            action: 'submit'
                        }).then(function(token) {
                            document.getElementById('recaptcha').value = token;
                        });
                    });
                </script>

                <button type="submit" class="btn btn-primary">{{ __('messages.login_button') }}</button>
            </form>
        </div>
    </div>
@endsection

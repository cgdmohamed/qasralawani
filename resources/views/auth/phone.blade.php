@extends('layouts.app')

@section('content')
    <div class="box p-4 text-light rounded">
        <div class=" p-2 mb-3">
            <h1 class="text-center fw-bold">
                {{ __('messages.gift_for_you') }}
            </h1>
            <p class="lead text-center fs-2">
                {{ __('messages.enter_your_phone_number_and_receive') }}
                <br>
                <strong class="fw-bold">{{ __('messages.15_discount_code') }}</strong>
            </p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="m-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('otp.request') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label"></label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg"
                            placeholder="{{ __('messages.name_placeholder') }}" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="email" class="form-control form-control-lg"
                            placeholder="{{ __('messages.email_placeholder') }}" value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">{{ __('messages.phone') }}</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control form-control-lg"
                            placeholder="{{ __('messages.phone_placeholder') }}" value="{{ old('phone_number') }}"
                            required>
                    </div>
                    <div class="box-footer rounded p-3 mt-5 mb-4">
                        <button type="submit"
                            class="btn btn-lg text-white fw-bold w-100 d-flex align-items-center justify-content-center"> <i
                                class="lni lni-phone"></i>
                            {{ __('messages.request_otp') }}</button>
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

                </form>
            </div>
        </div>
    </div>
@endsection

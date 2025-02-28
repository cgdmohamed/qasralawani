@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3>{{ __('messages.enter_your_details') }}</h3>

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
                    <label for="name" class="form-label">{{ __('messages.name') }}</label>
                    <input type="text" name="name" id="name" class="form-control"
                        placeholder="{{ __('messages.name_placeholder') }}"
                        value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('messages.email') }}</label>
                    <input type="email" name="email" id="email" class="form-control"
                        placeholder="{{ __('messages.email_placeholder') }}" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control"
                        placeholder="{{ __('messages.phone_placeholder') }}" value="{{ old('phone_number') }}" required>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('messages.request_otp') }}</button>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <h3>Enter the OTP Code</h3>

        <!-- Error / success messages -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- We assume 'phone_number' is stored in session or passed along. -->
        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf

            <input type="hidden" name="phone_number" 
                   value="{{ session('phone_number') ?? old('phone_number') }}">

            <div class="mb-3">
                <label for="otp_code" class="form-label">OTP Code</label>
                <input type="text" name="otp_code" id="otp_code" 
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Verify OTP</button>
        </form>
    </div>
</div>
@endsection

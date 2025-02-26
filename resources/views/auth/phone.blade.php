@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <h3>Enter Your Phone Number</h3>

        <!-- Display any error messages -->
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
                <label for="phone_number" class="form-label">Phone (KSA)</label>
                <input type="text" name="phone_number" id="phone_number" 
                       class="form-control" placeholder="05XXXXXXXX or +9665XXXXXXXX" 
                       value="{{ old('phone_number') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Request OTP</button>
        </form>
    </div>
</div>
@endsection

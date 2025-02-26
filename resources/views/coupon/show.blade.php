@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">

        <h3 class="mb-4">Congratulations!</h3>
        <p>Your coupon code is:</p>
        <div class="alert alert-success display-5" role="alert">
            <strong>{{ $couponCode }}</strong>
        </div>

        <p>We've also sent this code to your phone via SMS.</p>
    </div>
</div>
@endsection

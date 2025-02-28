@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">

        <h3 class="mb-4">{{ __('messages.congratulations') }}</h3>
        <p>{{ __('messages.your_coupon_code') }}</p>
        <div class="alert alert-success display-5" role="alert">
            <strong>{{ $couponCode }}</strong>
        </div>

        <p>{{ __('messages.coupon_sent_sms') }}</p>
    </div>
</div>
@endsection

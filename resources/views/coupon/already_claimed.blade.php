@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">

        <h3>{{ __('messages.coupon_already_claimed') }}</h3>
        <div class="alert alert-info">
            {{ __('messages.coupon_message') }}
        </div>

    </div>
</div>
@endsection

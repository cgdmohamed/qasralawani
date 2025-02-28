@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">

        <h3>{{ __('messages.sorry') }}</h3>
        <div class="alert alert-warning">
            {{ __('messages.no_coupons_available') }}
        </div>

    </div>
</div>
@endsection

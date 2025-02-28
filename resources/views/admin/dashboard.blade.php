@extends('layouts.admin')

@section('content')
<h2>{{ __('messages.admin_dashboard') }}</h2>
<hr/>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-header">{{ __('messages.total_users') }}</div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalUsers }}</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-header">{{ __('messages.used_coupons') }}</div>
            <div class="card-body">
                <h5 class="card-title">{{ $usedCoupons }}</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-header">{{ __('messages.unused_coupons') }}</div>
            <div class="card-body">
                <h5 class="card-title">{{ $unusedCoupons }}</h5>
            </div>
        </div>
    </div>
</div>

<!-- Import & Export -->
<div class="row">
    <div class="col-md-6">
        <h4>{{ __('messages.import_coupons') }}</h4>
        @if(session('success'))
            <div class="alert alert-success">{{ __('messages.success_message') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ __('messages.error_message') }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.import.coupons') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="csv_file" class="form-label">{{ __('messages.csv_file_label') }}</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('messages.import_button') }}</button>
            <a href="{{ route('admin.demo.csv') }}" class="btn btn-info">
                {{ __('messages.download_demo_csv') }}
            </a>
        </form>

    </div>

    <div class="col-md-6">
        <h4>{{ __('messages.export_coupons') }}</h4>
        <a href="{{ route('admin.export.coupons') }}" class="btn btn-secondary">
            {{ __('messages.export_button') }}
        </a>
    </div>
</div>
@endsection

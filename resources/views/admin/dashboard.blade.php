<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin')

@section('content')
<h2>Admin Dashboard</h2>
<hr/>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-header">Total Users</div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalUsers }}</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-header">Used Coupons</div>
            <div class="card-body">
                <h5 class="card-title">{{ $usedCoupons }}</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-header">Unused Coupons</div>
            <div class="card-body">
                <h5 class="card-title">{{ $unusedCoupons }}</h5>
            </div>
        </div>
    </div>
</div>

<!-- Import & Export -->
<div class="row">
    <div class="col-md-6">
        <h4>Import Coupons</h4>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

        <form action="{{ route('admin.import.coupons') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File (only coupon codes)</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
            <a href="{{ route('admin.demo.csv') }}" class="btn btn-info">
                Download Demo CSV File
            </a>
        </form>

    </div>

    <div class="col-md-6">
        <h4>Export Coupons</h4>
        <a href="{{ route('admin.export.coupons') }}" class="btn btn-secondary">
            Export Coupons as CSV
        </a>
    </div>
</div>
@endsection

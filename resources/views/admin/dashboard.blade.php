@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Admin Dashboard</h2>
        <hr/>

        <!-- Display analytics (example) -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Total Users</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalUsers }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Used Coupons</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $usedCoupons }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Unused Coupons</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $unusedCoupons }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart example (if using a JS library) -->
        {{-- 
        <canvas id="signupChart" width="400" height="150"></canvas>
        <script>
            const data = @json($dailySignups);
            // You can integrate Chart.js or any library to display the data
        </script>
        --}}

        <hr/>

        <!-- Import Coupons Form -->
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

        <form action="{{ route('admin.import.coupons') }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>

        <!-- Export Coupons Link -->
        <h4>Export Coupons</h4>
        <a href="{{ route('admin.export.coupons') }}" class="btn btn-secondary">Export Coupons as CSV</a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        
        <a href="{{ route('admin.successful.coupons') }}" class="btn btn-info">
            View Used Coupons
        </a>
        
    </div>
</div>
@endsection

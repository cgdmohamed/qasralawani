@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>{{ __('messages.admin_stats') }}</h2>
        <hr />

        <!-- Overall Metrics -->
        <div class="row mb-4">
            <!-- Total Subscribers -->
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-header">{{ __('messages.total_subscribers') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalSubscribers }}</h5>
                    </div>
                </div>
            </div>
            <!-- Total Coupons -->
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-header">{{ __('messages.total_coupons') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalCoupons }}</h5>
                    </div>
                </div>
            </div>
            <!-- Used Coupons -->
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-header">{{ __('messages.used_coupons') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $usedCoupons }}</h5>
                    </div>
                </div>
            </div>
            <!-- Unused Coupons -->
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-header">{{ __('messages.unused_coupons') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $unusedCoupons }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- OTP Stats -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-secondary">
                    <div class="card-header">{{ __('messages.otp_requests_successful') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalOtpSuccess }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-danger">
                    <div class="card-header">{{ __('messages.otp_requests_failed') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalOtpFailed }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-8">
                <div class="mb-4">
                    <h4>{{ __('messages.daily_otp_requests') }}</h4>
                    <canvas id="dailyOtpChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-4">
                    <h4>{{ __('messages.used_vs_unused_coupons') }}</h4>
                    <canvas id="couponsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Use the UMD build of Chart.js to avoid module issues -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daily OTP Requests Chart (Line Chart)
            const dailyLabels = @json($dailyLabels);
            const dailyCounts = @json($dailyCounts);
            const ctx1 = document.getElementById('dailyOtpChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: '{{ __('messages.daily_otp_requests') }}',
                        data: dailyCounts,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Used vs Unused Coupons Chart (Doughnut Chart)
            const ctx2 = document.getElementById('couponsChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['{{ __('messages.used_coupons') }}', '{{ __('messages.unused_coupons') }}'],
                    datasets: [{
                        data: [{{ $usedCoupons }}, {{ $unusedCoupons }}],
                        backgroundColor: [
                            'rgb(75, 192, 192)',
                            'rgb(255, 159, 64)'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
    </script>
@endsection

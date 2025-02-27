@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Coupon Stats</h2>
    <hr/>

    <!-- 1. Daily Claims Chart -->
    <div class="mb-4">
        <h4>Daily Claims</h4>
        <canvas id="dailyClaimsChart" width="400" height="200"></canvas>
    </div>

    <!-- 2. Used vs Unused Chart -->
    <div class="mb-4">
        <h4>Used vs. Unused</h4>
        <canvas id="usedUnusedChart" width="400" height="200"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyLabels = @json($labels);
    const dailyCounts = @json($counts);

    // Daily claims chart
    const ctx1 = document.getElementById('dailyClaimsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Coupons Claimed',
                data: dailyCounts,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Used vs. Unused
    const usedUnusedData = {
        labels: ['Used', 'Unused'],
        datasets: [{
            data: [{{ $totalUsed }}, {{ $totalUnused }}],
            backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 99, 132)'],
        }]
    };
    const ctx2 = document.getElementById('usedUnusedChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: usedUnusedData,
        options: {
            responsive: true
        }
    });
});
</script>
@endsection

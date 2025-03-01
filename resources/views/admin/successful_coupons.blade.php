@extends('layouts.admin')

@section('content')
    <h2>{{ __('messages.used_coupons') }}</h2>
    {{-- <input type="text" id="search-coupon" placeholder="Search coupons...">
    <ul id="coupon-results"></ul> --}}
    <hr />
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('messages.id') }}</th>
                <th>{{ __('messages.code') }}</th>
                <th>{{ __('messages.used_by_phone') }}</th>
                <th>{{ __('messages.used_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->id }}</td>
                    <td>{{ $coupon->code }}</td>
                    <td>
                        {{ $coupon->subscriber ? $coupon->subscriber->phone_number : 'N/A' }}
                    </td>
                    <td>{{ $coupon->used_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">{{ __('messages.no_used_coupons') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $coupons->links() }}

    <script>
        document.getElementById('search-coupon').addEventListener('input', function() {
            let query = this.value;
            if (query.length > 2) {
                fetch(`/coupons/search?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let results = document.getElementById('coupon-results');
                        results.innerHTML = "";
                        data.forEach(coupon => {
                            let li = document.createElement('li');
                            li.textContent = `${coupon.code} - ${coupon.description}`;
                            results.appendChild(li);
                        });
                    });
            }
        });
    </script>
@endsection

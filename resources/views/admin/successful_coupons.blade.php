@extends('layouts.admin')

@section('content')
    <h2>Used Coupons</h2>
    <hr />
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Used By (Phone)</th>
                <th>Used At</th>
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
                    <td colspan="4">No used coupons.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $coupons->links() }}
@endsection

@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Successfully Registered Coupons</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Coupon ID</th>
                    <th>Coupon Code</th>
                    <th>Used By (Phone)</th>
                    <th>Used At</th> <!-- or Issued At, etc. -->
                </tr>
            </thead>
            <tbody>
            @forelse ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->id }}</td>
                    <td>{{ $coupon->code }}</td>
                    <td>
                        {{ $coupon->user ? $coupon->user->phone_number : 'N/A' }}
                    </td>
                    <td>
                        {{ $coupon->updated_at->format('Y-m-d H:i:s') }}
                        {{-- or $coupon->used_at if you have a dedicated column --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No used coupons found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <!-- If you used paginate() above -->
        {{ $coupons->links() }}
    </div>
</div>
@endsection

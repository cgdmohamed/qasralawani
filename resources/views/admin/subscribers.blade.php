@extends('layouts.admin')

@section('content')
    <h2>Subscribers List</h2>

    <div class="mb-3">
        <a href="{{ route('admin.export.subscribers') }}" class="btn btn-info">
            Download Subscribers CSV
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Subscribed At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscribers as $subscriber)
                <tr>
                    <td>{{ $subscriber->id }}</td>
                    <td>{{ $subscriber->name }}</td>
                    <td>{{ $subscriber->email ?? 'N/A' }}</td>
                    <td>{{ $subscriber->phone_number }}</td>
                    <td>{{ $subscriber->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No subscribers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $subscribers->links() }}
@endsection

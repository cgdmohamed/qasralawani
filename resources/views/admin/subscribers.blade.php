@extends('layouts.admin')

@section('content')
    <h2>{{ __('messages.subscribers_list') }}</h2>

    <div class="mb-3">
        <a href="{{ route('admin.export.subscribers') }}" class="btn btn-info">
            {{ __('messages.download_subscribers_csv') }}
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('messages.id') }}</th>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.email') }}</th>
                <th>{{ __('messages.phone_number') }}</th>
                <th>{{ __('messages.subscribed_at') }}</th>
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
                    <td colspan="5">{{ __('messages.no_subscribers') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $subscribers->links() }}
@endsection

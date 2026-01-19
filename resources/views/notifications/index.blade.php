<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Notifications</h5>
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">Mark All as Read</button>
            </form>
        </div>
        <div class="card-body">
            <div class="list-group">
                @forelse($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-link text-decoration-none">Mark as Read</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="text-center p-3">No notifications found.</div>
                @endforelse
            </div>
            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

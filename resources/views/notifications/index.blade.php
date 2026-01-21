<x-app-layout>
    <div class="card mt-2 mx-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Notifications</h5>
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">Mark All as Read</button>
            </form>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('notifications.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search Notifications..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" class="form-control" placeholder="From Date"
                            value="{{ request('from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" class="form-control" placeholder="To Date"
                            value="{{ request('to') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('notifications.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>
            <hr>

            <div class="list-group">
                @forelse($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                        @if (!$notification->read_at)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-link text-decoration-none">Mark as
                                    Read</button>
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

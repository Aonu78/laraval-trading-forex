<div class="row g-sm-4 g-3">
    <div class="col-12">
        <div class="site-card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4 class="mb-0">{{ __('Notifications') }}</h4>
                @if ($notifications->whereNull('read_at')->count() > 0)
                    <form action="{{ route('user.notifications.mark-all') }}" method="post">
                        @csrf
                        <button type="submit" class="btn main-btn btn-sm">
                            {{ __('Mark All As Read') }}
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @forelse ($notifications as $notification)
                    <div class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}">
                        <div>
                            <h6 class="mb-1">
                                {{ $notification->data['title'] ?? __('Notification') }}
                            </h6>
                            <p class="mb-2">{{ $notification->data['message'] ?? '' }}</p>
                            <small class="text-muted">
                                {{ __('From') }}: {{ $notification->data['sent_by'] ?? 'admin' }}
                                • {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="notification-actions">
                            @if (!empty($notification->data['url']))
                                <a href="{{ $notification->data['url'] }}" class="btn main-btn btn-sm">
                                    {{ __('Open') }}
                                </a>
                            @endif
                            @if (!$notification->read_at)
                                <form action="{{ route('user.notifications.mark-read', $notification->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        {{ __('Mark Read') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="mb-0">{{ __('No notifications found') }}</p>
                    </div>
                @endforelse

                @if ($notifications->hasPages())
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .notification-item {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            margin-bottom: 16px;
            background: #fff;
        }

        .notification-item.is-unread {
            border-left: 4px solid #f59e0b;
        }

        .notification-item.is-read {
            border-left: 4px solid #10b981;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        @media (max-width: 767px) {
            .notification-item {
                flex-direction: column;
            }
        }
    </style>
@endpush

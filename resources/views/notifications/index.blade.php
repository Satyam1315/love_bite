<x-app-layout pageTitle="Notifications">
    <div class="space-y-6">
        <div class="flex justify-end">
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button class="rounded-xl border border-orange-200 px-4 py-2.5 text-sm font-bold text-orange-700 hover:bg-orange-50/50 transition active:scale-[0.98]">Mark all as read</button>
            </form>
        </div>

        @forelse($notifications as $notification)
            <article class="lb-card p-5 {{ $notification->read_at ? 'opacity-80' : '' }}">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $notification->title }}</h3>
                        <p class="mt-1 text-sm text-gray-700 font-semibold leading-relaxed">{{ $notification->message }}</p>
                        <p class="mt-2 text-xs text-gray-400 font-bold">{{ $notification->created_at->format('d M Y, h:i A') }}</p>
                    </div>

                    <div class="flex gap-2 shrink-0">
                        @if($notification->link)
                            <a href="{{ $notification->link }}" class="rounded-xl border border-gray-300 px-4 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-100 transition active:scale-[0.98]">Open</a>
                        @endif

                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                @method('PATCH')
                                <button class="rounded-xl border border-green-200 px-4 py-1.5 text-xs font-bold text-green-700 hover:bg-green-50 transition active:scale-[0.98]">Mark read</button>
                            </form>
                        @else
                            <span class="rounded-xl bg-green-100 px-4 py-1.5 text-xs font-bold text-green-700">Read</span>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="lb-card p-8 text-center text-gray-500 italic font-bold">No notifications yet.</div>
        @endforelse

        <div>{{ $notifications->links() }}</div>
    </div>
</x-app-layout>

<x-admin-layout pageTitle="Settings">
    <div class="max-w-2xl space-y-6">

            {{-- Live Status Card --}}
            <div class="lb-card p-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Current Status</p>
                    @if($isOpen)
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-lg font-black text-green-700">Open for Orders</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Customers can place orders right now.</p>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-lg font-black text-red-700">Closed</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ \App\Models\Setting::closedMessage() }}</p>
                    @endif
                </div>
                <div class="text-4xl">{{ $isOpen ? '🟢' : '🔴' }}</div>
            </div>

            {{-- Settings Form --}}
            <form method="POST" action="{{ route('admin.settings.update') }}" class="lb-card p-6 space-y-6">
                @csrf

                <h3 class="font-bold text-gray-900 text-lg display-font">Opening Hours</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5" for="opening_time">Opens At</label>
                        <input type="time" id="opening_time" name="opening_time" value="{{ $settings['opening_time'] }}"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition font-semibold">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5" for="closing_time">Closes At</label>
                        <input type="time" id="closing_time" name="closing_time" value="{{ $settings['closing_time'] }}"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition font-semibold">
                    </div>
                </div>
                <p class="text-xs text-gray-400 -mt-2">Default: Opens 1:00 PM, Closes 10:00 PM. Outside these hours, customers see a "closed" banner.</p>

                <hr class="border-gray-100">

                <h3 class="font-bold text-gray-900 text-lg display-font">Manual Overrides</h3>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="flex items-center gap-4 cursor-pointer group p-4 rounded-xl border border-gray-200 hover:border-green-200 hover:bg-green-50/30 transition">
                        <div class="relative flex-shrink-0">
                            <input type="hidden" name="is_force_opened" value="0">
                            <input type="checkbox" id="is_force_opened" name="is_force_opened" value="1"
                                {{ $settings['is_force_opened'] ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-200 peer-checked:bg-green-500 rounded-full transition peer-focus:ring-2 peer-focus:ring-green-200"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-6"></div>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">Force Open Restaurant</p>
                            <p class="text-xs text-gray-500 mt-0.5">When enabled, the restaurant is shown as open regardless of opening hours. Bypasses all auto-closures.</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-4 cursor-pointer group p-4 rounded-xl border border-gray-200 hover:border-red-200 hover:bg-red-50/30 transition">
                        <div class="relative flex-shrink-0">
                            <input type="hidden" name="is_manually_closed" value="0">
                            <input type="checkbox" id="is_manually_closed" name="is_manually_closed" value="1"
                                {{ $settings['is_manually_closed'] ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-200 peer-checked:bg-red-500 rounded-full transition peer-focus:ring-2 peer-focus:ring-red-200"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-6"></div>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">Force Close Restaurant</p>
                            <p class="text-xs text-gray-500 mt-0.5">When enabled, the restaurant is shown as closed regardless of opening hours.</p>
                        </div>
                    </label>
                </div>

                <hr class="border-gray-100">

                <h3 class="font-bold text-gray-900 text-lg display-font">Closed Message</h3>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5" for="closed_message">Message shown to customers</label>
                    <textarea id="closed_message" name="closed_message" rows="3"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition resize-none"
                        placeholder="e.g. We are currently closed...">{{ $settings['closed_message'] }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">
                        Use <code class="bg-gray-100 px-1 rounded">{opening_time}</code> and <code class="bg-gray-100 px-1 rounded">{closing_time}</code> as placeholders — they'll be replaced with the actual times.
                    </p>
                </div>

                <div class="pt-2 flex items-center gap-4">
                    <button type="submit"
                        class="px-8 py-3 bg-orange-600 text-white text-sm font-bold rounded-xl hover:bg-orange-700 transition active:scale-[0.98] shadow-lg shadow-orange-500/20">
                        Save Settings
                    </button>
                    <span class="text-xs text-gray-400">Changes apply immediately site-wide.</span>
                </div>
            </form>

    </div>
</x-admin-layout>

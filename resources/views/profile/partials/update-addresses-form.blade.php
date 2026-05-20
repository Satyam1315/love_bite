<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Saved Addresses') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Manage your saved delivery locations for a faster Swiggy/Zomato style checkout experience.") }}
        </p>
    </header>

    {{-- Saved Addresses List --}}
    <div class="space-y-4">
        @forelse($addresses as $address)
            <div class="flex items-start justify-between p-4 border rounded-xl transition duration-150
                {{ $address->is_default ? 'border-orange-500 bg-orange-50/20' : 'border-orange-100 hover:bg-orange-50/10' }}">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider
                            {{ $address->type === 'home' ? 'bg-blue-100 text-blue-700' : ($address->type === 'work' ? 'bg-purple-100 text-purple-700' : 'bg-pink-100 text-pink-700') }}">
                            {{ $address->type === 'home' ? '🏠 Home' : ($address->type === 'work' ? '💼 Work' : '📍 Other') }}
                        </span>
                        @if($address->is_default)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-orange-600 text-white">
                                Default
                            </span>
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-gray-700 font-semibold pr-4 leading-relaxed">
                        {{ $address->address_line }}
                    </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @if(!$address->is_default)
                        <form method="POST" action="{{ route('profile.addresses.default', $address) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs font-bold text-orange-600 hover:text-orange-700 hover:underline">
                                Make Default
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('profile.addresses.delete', $address) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-700 hover:underline">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 italic">No saved addresses yet. Add one below!</p>
        @endforelse
    </div>

    {{-- Add New Address Form --}}
    <form method="POST" action="{{ route('profile.addresses.store') }}" class="mt-6 space-y-6 border-t border-orange-100 pt-6">
        @csrf

        <h3 class="text-md font-bold text-gray-900">Add New Address</h3>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5" for="address_type">Address Type</label>
                <select id="address_type" name="type" required
                        class="w-full px-4 py-2.5 text-sm border border-orange-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-semibold">
                    <option value="home">🏠 Home</option>
                    <option value="work">💼 Work</option>
                    <option value="other">📍 Other</option>
                </select>
                @error('type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5" for="address_line">Address Details</label>
                <input type="text" id="address_line" name="address_line" placeholder="Flat/House No., Building, Street Name, Pincode" required
                       class="w-full px-4 py-2.5 text-sm border border-orange-200 rounded-xl focus:border-orange-400 focus:ring-1 focus:ring-orange-200 transition font-semibold placeholder-gray-400">
                @error('address_line')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-sm font-bold text-white rounded-xl transition active:scale-[0.98] shadow-md shadow-orange-100">
                Save Address
            </button>
        </div>
    </form>
</section>

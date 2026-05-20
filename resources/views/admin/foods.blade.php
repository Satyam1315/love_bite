<x-admin-layout pageTitle="Food Items">
    <div class="space-y-6">

            <div class="lb-card p-5">
                <h3 class="text-lg font-bold">Add Food Item</h3>
                <form method="POST" action="{{ route('admin.foods.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-3">
                    @csrf
                    <input type="text" name="name" placeholder="Food Name" class="rounded-lg border border-orange-200 px-4 py-2 text-sm" required>
                    <select name="category_id" class="rounded-lg border border-orange-200 px-4 py-2 text-sm" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="type" class="rounded-lg border border-orange-200 px-4 py-2 text-sm" required>
                        <option value="veg">Veg</option>
                        <option value="non-veg">Non-Veg</option>
                    </select>
                    <input type="number" step="0.01" min="0" name="price_half" placeholder="Half Price" class="rounded-lg border border-orange-200 px-4 py-2 text-sm">
                    <input type="number" step="0.01" min="0" name="price_full" placeholder="Full Price" class="rounded-lg border border-orange-200 px-4 py-2 text-sm">
                    <input type="file" name="image" class="rounded-lg border border-orange-200 px-4 py-2 text-sm">
                    <label class="flex items-center gap-2 rounded-lg border border-orange-200 px-4 py-2 text-sm">
                        <input type="checkbox" name="is_available" value="1" checked>
                        <span>Available for ordering</span>
                    </label>
                    <button class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-bold text-white md:col-span-3">Create Food</button>
                </form>
            </div>

            <div class="lb-card p-5">
                <h3 class="text-lg font-bold">Food List</h3>
                <div class="mt-4 overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-gray-600">
                                <th class="px-3 py-2">Item</th>
                                <th class="px-3 py-2">Category</th>
                                <th class="px-3 py-2">Half</th>
                                <th class="px-3 py-2">Full</th>
                                <th class="px-3 py-2">Availability</th>
                                <th class="px-3 py-2">Type</th>
                                <th class="px-3 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($foods as $food)
                                <tr class="border-b border-orange-50">
                                    <td class="px-3 py-2 font-semibold">{{ $food->name }}</td>
                                    <td class="px-3 py-2">{{ $food->category->name ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $food->price_half }}</td>
                                    <td class="px-3 py-2">{{ $food->price_full }}</td>
                                    <td class="px-3 py-2">
                                        <span class="rounded-full px-2 py-1 text-xs font-bold {{ $food->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $food->is_available ? 'Available' : 'Not Available' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">{{ strtoupper($food->type) }}</td>
                                    <td class="px-3 py-2">
                                        <div class="flex flex-wrap gap-2">
                                            <form method="POST" action="{{ route('admin.foods.update', $food) }}" class="grid gap-2 md:grid-cols-3" enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <input name="name" value="{{ $food->name }}" class="rounded border border-orange-200 px-2 py-1 text-xs" />
                                                <select name="category_id" class="rounded border border-orange-200 px-2 py-1 text-xs">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" @selected($category->id === $food->category_id)>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <select name="type" class="rounded border border-orange-200 px-2 py-1 text-xs">
                                                    <option value="veg" @selected($food->type === 'veg')>Veg</option>
                                                    <option value="non-veg" @selected($food->type === 'non-veg')>Non-Veg</option>
                                                </select>
                                                <input name="price_half" value="{{ $food->price_half }}" class="rounded border border-orange-200 px-2 py-1 text-xs" />
                                                <input name="price_full" value="{{ $food->price_full }}" class="rounded border border-orange-200 px-2 py-1 text-xs" />
                                                <label class="flex items-center gap-1 rounded border border-orange-200 px-2 py-1 text-xs">
                                                    <input type="checkbox" name="is_available" value="1" @checked($food->is_available)>
                                                    Available
                                                </label>
                                                <input type="file" name="image" class="rounded border border-orange-200 px-2 py-1 text-xs" />
                                                <button type="submit" class="rounded border border-gray-300 px-2 py-1 text-xs font-bold">Update</button>
                                                <button type="submit" form="delete-form-{{ $food->id }}" class="rounded border border-red-200 px-2 py-1 text-xs font-bold text-red-700">Delete</button>
                                            </form>
                                            <form id="delete-form-{{ $food->id }}" method="POST" action="{{ route('admin.foods.delete', $food) }}" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $foods->links() }}</div>
            </div>
    </div>
</x-admin-layout>

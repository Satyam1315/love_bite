<x-admin-layout pageTitle="Categories">
    <div class="space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
            @endif

            <div class="lb-card p-5">
                <h3 class="text-lg font-bold">Add New Category</h3>
                <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4 flex flex-wrap items-end gap-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold">Category Name</label>
                        <input type="text" name="name" class="rounded-lg border border-orange-200 px-4 py-2 text-sm" required>
                    </div>
                    <button class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-bold text-white">Create</button>
                </form>
                @error('name')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="lb-card p-5">
                <h3 class="text-lg font-bold">Existing Categories</h3>
                <div class="mt-4 overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-gray-600">
                                <th class="px-3 py-2">Name</th>
                                <th class="px-3 py-2">Foods</th>
                                <th class="px-3 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr class="border-b border-orange-50">
                                    <td class="px-3 py-2 font-semibold">{{ $category->name }}</td>
                                    <td class="px-3 py-2">{{ $category->foods_count }}</td>
                                    <td class="px-3 py-2">
                                        <div class="flex flex-wrap gap-2">
                                            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input name="name" value="{{ $category->name }}" class="rounded-lg border border-orange-200 px-3 py-1 text-sm" />
                                                <button class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-bold">Update</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.categories.delete', $category) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-lg border border-red-200 px-3 py-1 text-xs font-bold text-red-700">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</x-admin-layout>

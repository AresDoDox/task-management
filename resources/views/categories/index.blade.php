<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categories
            </h2>
            <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Category
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($categories->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500">No categories yet.
                        <a href="{{ route('categories.create') }}" class="text-blue-600">Create one</a>
                    </p>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Color</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tasks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $category->name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded"
                                                style="background-color: {{ $category->color }};"></div>
                                            <span class="text-sm text-gray-500">{{ $category->color }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $category->tasks()->count() }}</td>
                                    <td class="flex gap-2 px-6 py-4 text-sm space-x-2">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            Edit
                                        </a>
                                        <x-delete-confirm-modal :route="route('categories.destroy', $category)" :id="$category->id" :name="trim($category->name)">
                                            <button class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                        </x-delete-confirm-modal>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

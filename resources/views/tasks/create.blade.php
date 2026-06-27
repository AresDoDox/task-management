<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Task - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('projects.tasks.store', $project) }}" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('title')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" id="priority"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" name="due_date" id="due_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign To</label>
                            <select name="assigned_to" id="assigned_to"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Not assigned</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">No category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('projects.show', $project) }}"
                            class="text-gray-700 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create
                            Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

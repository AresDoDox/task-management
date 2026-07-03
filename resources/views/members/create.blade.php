<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Member - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('projects.members.store', $project) }}" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                        <select name="user_id" id="user_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Select a user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer</option>
                        </select>
                        @error('role')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('projects.members', $project) }}"
                            class="text-gray-700 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add
                            Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

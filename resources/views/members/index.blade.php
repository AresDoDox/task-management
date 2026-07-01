<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }} - Members
            </h2>
            @if ($project->isOwner(Auth::user()))
                <a href="{{ route('projects.members.create', $project) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Add Member
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($members as $member)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium">{{ $member->user->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $member->user->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($project->isOwner(Auth::user()) && $member->role !== 'owner')
                                        <form method="POST" action="{{ route('members.update', $member) }}"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <select name="role" class="text-sm rounded border-gray-300"
                                                onchange="this.form.submit()">
                                                <option value="member"
                                                    {{ $member->role === 'member' ? 'selected' : '' }}>Member</option>
                                                <option value="admin"
                                                    {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="viewer"
                                                    {{ $member->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ ucfirst($member->role) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $member->joined_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($project->isOwner(Auth::user()) && $member->role !== 'owner')
                                        <x-delete-confirm-modal :route="route('members.destroy', $member)" :id="$member->id" :name="trim($member->user->name)">
                                            <button class="text-red-600 hover:text-red-900 ml-2">Remove</button>
                                        </x-delete-confirm-modal>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No members yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($members->hasPages())
                <div class="mt-6">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

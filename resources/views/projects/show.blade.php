<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            @if ($project->isOwner(auth()->user()))
                <div class="flex gap-2">
                    <a href="{{ route('projects.members', $project) }}"
                        class="text-blue-600 hover:text-blue-800">Members</a>
                    <span> | </span>
                    <a href="{{ route('projects.edit', $project) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                    <x-delete-confirm-modal :route="route('projects.destroy', $project)" :id="$project->id" :name="trim($project->name)">
                        <button class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                    </x-delete-confirm-modal>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <p class="text-gray-600">{!! nl2br(e($project->description)) !!}</p>
            </div>

            <!-- Tasks Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-semibold text-lg">Tasks</h3>
                    <a href="{{ route('projects.tasks.create', $project) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Add Task
                    </a>
                </div>

                {{-- Filter Form --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <form method="GET" action="{{ route('projects.show', $project) }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <input type="text" name="search" placeholder="Search tasks..."
                                    value="{{ request('search') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">All Status</option>
                                    <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>Todo
                                    </option>
                                    <option value="in_progress"
                                        {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done
                                    </option>
                                </select>
                            </div>

                            <!-- Priority Filter -->
                            <div>
                                <select name="priority" class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">All Priority</option>
                                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low
                                    </option>
                                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>
                                        Medium</option>
                                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High
                                    </option>
                                </select>
                            </div>

                            <!-- Assigned To Filter -->
                            <div>
                                <select name="assigned_to" class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">All Members</option>
                                    @foreach ($project->members as $member)
                                        <option value="{{ $member->user_id }}"
                                            {{ request('assigned_to') == $member->user_id ? 'selected' : '' }}>
                                            {{ $member->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <select name="category" class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="created_at"
                                        {{ request('sort') === 'created_at' ? 'selected' : '' }}>Newest</option>
                                    <option value="due_date" {{ request('sort') === 'due_date' ? 'selected' : '' }}>Due
                                        Date</option>
                                    <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>
                                        Priority</option>
                                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title
                                        A-Z</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Filter
                                </button>
                                <a href="{{ route('projects.show', $project) }}"
                                    class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($tasks->isEmpty())
                    <p class="text-gray-500">No tasks yet</p>
                @else
                    <div class="space-y-4">
                        @foreach ($tasks as $task)
                            <div class="border rounded-lg p-4 hover:shadow-lg transition mb-2">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-semibold">{{ $task->title }}</h4>
                                    <span
                                        class="text-xs px-2 py-1 rounded
                                        @if ($task->priority === 'high') bg-red-100 text-red-800
                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm">View Details →</a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

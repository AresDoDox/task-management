<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            @if ($project->isOwner(auth()->user()))
                <div class="flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800"
                            onclick="return confirm('Sure?')">Delete</button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <p class="text-gray-600">{{ $project->description }}</p>
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
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

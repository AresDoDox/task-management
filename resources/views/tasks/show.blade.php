<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $task->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('projects.tasks.edit', [$task->project, $task]) }}"
                    class="text-blue-600 hover:text-blue-800">Edit</a>
                <x-delete-confirm-modal :route="route('projects.tasks.destroy', [$task->project, $task])" :id="$task->id" :name="trim($task->title)">
                    <button class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                </x-delete-confirm-modal>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="col-span-2 space-y-6">
                    <!-- Description -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold mb-4">Description</h3>
                        <p class="text-gray-600">
                            {!! $task->description ? nl2br(e($task->description)) : 'No description' !!}</p>
                    </div>

                    <!-- Subtasks -->
                    @if ($task->subtasks->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="font-semibold mb-4">Subtasks
                                ({{ $task->subtasks->where('is_completed', true)->count() }}/{{ $task->subtasks->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach ($task->subtasks as $subtask)
                                    <div class="flex items-center">
                                        <input type="checkbox" {{ $subtask->is_completed ? 'checked' : '' }}
                                            class="w-4 h-4">
                                        <span
                                            class="ml-2 {{ $subtask->is_completed ? 'line-through text-gray-400' : '' }}">
                                            {{ $subtask->title }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <!-- Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2">
                        <h4 class="font-semibold mb-2">Status</h4>
                        <span
                            class="inline-block px-3 py-1 rounded text-xs font-semibold
                            @if ($task->status === 'done') bg-green-100 text-green-800
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>

                    <!-- Priority -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2">
                        <h4 class="font-semibold mb-2">Priority</h4>
                        <span
                            class="inline-block px-3 py-1 rounded text-xs font-semibold
                            @if ($task->priority === 'high') bg-red-100 text-red-800
                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <!-- Assigned To -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2">
                        <h4 class="font-semibold mb-2">Assigned To</h4>
                        <p class="text-sm">{{ $task->assignee->name ?? 'Not assigned' }}</p>
                    </div>

                    <!-- Due Date -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2">
                        <h4 class="font-semibold mb-2">Due Date</h4>
                        <p class="text-sm">{{ $task->due_date?->format('M d, Y') ?? 'No due date' }}</p>
                    </div>

                    <!-- Complete Button -->
                    @if ($task->status !== 'done')
                        <form method="POST" action="{{ route('projects.tasks.complete', [$task->project, $task]) }}">
                            @csrf
                            <button type="submit"
                                class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Mark as Complete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

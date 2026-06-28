<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 text-sm">Total Projects</p>
                    <p class="text-3xl font-bold">{{ $totalProjects }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 text-sm">Total Tasks</p>
                    <p class="text-3xl font-bold">{{ $totalTasks }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 text-sm">Completed</p>
                    <p class="text-3xl font-bold text-green-600">{{ $completedTasks }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $pendingTasks }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 text-sm">Overdue</p>
                    <p class="text-3xl font-bold text-red-600">{{ $overdueTasks }}</p>
                </div>
            </div>

            <!-- Recent Projects & Tasks -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Projects -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Recent Projects</h3>
                    <div class="space-y-2">
                        @forelse($recentProjects as $project)
                            <a href="{{ route('projects.show', $project) }}"
                                class="block p-3 hover:bg-gray-50 rounded border">
                                {{ $project->name }}
                                <span class="text-xs text-gray-500">{{ $project->tasks->count() ?? 0 }} tasks</span>
                            </a>
                        @empty
                            <p class="text-gray-500">No projects yet</p>
                        @endforelse
                    </div>
                    <a href="{{ route('projects.index') }}"
                        class="text-blue-600 hover:text-blue-800 text-sm mt-4 block">View all projects →</a>
                </div>

                <!-- Recent Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Recent Tasks</h3>
                    <div class="space-y-2">
                        @forelse($recentTasks as $task)
                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                class="block p-3 hover:bg-gray-50 rounded border">
                                {{ $task->title }}
                                <span class="text-xs text-gray-500">{{ $task->project->name }}</span>
                            </a>
                        @empty
                            <p class="text-gray-500">No tasks yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

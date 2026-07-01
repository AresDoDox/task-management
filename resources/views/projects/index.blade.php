<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projects
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('projects.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Create New Project
                </a>
            </div>

            @if ($projects->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500">No projects yet. <a href="{{ route('projects.create') }}"
                            class="text-blue-600">Create one</a></p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($projects as $project)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition">
                            <h3 class="font-bold text-lg mb-2">{{ $project->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{!! nl2br(e($project->description)) !!}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $project->tasks->count() }} tasks</span>
                                <a href="{{ route('projects.show', $project) }}"
                                    class="text-blue-600 hover:text-blue-800">View →</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="mt-6">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

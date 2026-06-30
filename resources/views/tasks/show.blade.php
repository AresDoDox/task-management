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

                    <!-- Subtasks Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">
                                Subtasks
                                <span class="text-sm text-gray-500">
                                    ({{ $task->subtasks->where('is_completed', true)->count() }}/{{ $task->subtasks->count() }})
                                </span>
                            </h3>
                        </div>

                        <!-- Add Subtask Form -->
                        @if ($task->status !== 'done')
                            <form method="POST" action="{{ route('subtasks.store', $task) }}" class="mb-6 flex gap-2">
                                @csrf
                                <input type="text" name="title" placeholder="Add a subtask..."
                                    class="flex-1 rounded-md border-gray-300 shadow-sm" required>
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Add
                                </button>
                            </form>
                        @endif

                        <!-- Subtasks List -->
                        <div class="space-y-2" id="subtasks-list">
                            @forelse($task->subtasks->sortBy('order') as $subtask)
                                <div class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 subtask-item"
                                    data-subtask-id="{{ $subtask->id }}">
                                    <input type="checkbox" class="w-4 h-4 subtask-toggle"
                                        data-subtask-id="{{ $subtask->id }}"
                                        {{ $subtask->is_completed ? 'checked' : '' }}
                                        @if ($task->status === 'done') disabled @endif>
                                    <span
                                        class="flex-1 {{ $subtask->is_completed ? 'line-through text-gray-400' : '' }}">
                                        {{ $subtask->title }}
                                    </span>

                                    @if ($task->status !== 'done')
                                        <x-delete-confirm-modal :route="route('subtasks.destroy', $subtask)" :id="$subtask->id" :name="trim($subtask->title)">
                                            <button class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                        </x-delete-confirm-modal>
                                        {{-- <button type="button"
                                            class="text-red-600 hover:text-red-800 text-sm delete-subtask"
                                            data-subtask-id="{{ $subtask->id }}">
                                            Delete
                                        </button> --}}
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No subtasks yet</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold mb-4">Comments ({{ $task->comments->count() }})</h3>

                        <!-- Add Comment Form -->
                        <form method="POST" action="{{ route('comments.store', $task) }}" class="mb-6">
                            @csrf
                            <textarea name="content" placeholder="Add a comment..." rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                            <button type="submit"
                                class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Comment</button>
                        </form>

                        <!-- Comments List -->
                        <div class="space-y-4">
                            @foreach ($task->comments as $comment)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <strong>{{ $comment->user->name }}</strong>
                                        <small
                                            class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-2">{!! nl2br(e($comment->content)) !!}</p>
                                    @if ($comment->user_id === auth()->id() || $task->project->isOwner(auth()->user()))
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle subtask completion
                // Toggle subtask completion
                document.querySelectorAll('.subtask-toggle').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const subtaskId = this.dataset.subtaskId;
                        const checkboxInput = this; // Lưu lại đối tượng checkbox vừa click

                        fetch(`/subtasks/${subtaskId}/toggle`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // 1. In log để kiểm tra
                                    console.log('Cập nhật thành công:', data.message);

                                    // 2. Cập nhật UI (Ví dụ: Tìm text subtask kế bên để gạch ngang chữ nếu đã hoàn thành)
                                    const labelText = checkboxInput.nextElementSibling;
                                    if (labelText) {
                                        if (checkboxInput.checked) {
                                            labelText.style.textDecoration = 'line-through';
                                            labelText.style.opacity = '0.5';
                                        } else {
                                            labelText.style.textDecoration = 'none';
                                            labelText.style.opacity = '1';
                                        }
                                    }
                                }

                                // Update compeleted
                                const counterElement = document.getElementById('subtask-counter');
                                if (counterElement) {
                                    // Tìm tất cả các checkbox subtask trên trang
                                    const allCheckboxes = document.querySelectorAll(
                                        '.subtask-toggle');
                                    // Đếm xem có bao nhiêu cái đang được tích chọn (checked)
                                    const totalSubtasks = allCheckboxes.length;
                                    const completedSubtasks = Array.from(allCheckboxes).filter(cb =>
                                        cb.checked).length;

                                    // Cập nhật lại chuỗi hiển thị dạng (đã_hoàn_thành/tổng_số)
                                    counterElement.textContent =
                                        `(${completedSubtasks}/${totalSubtasks})`;
                                }
                            })
                            .catch(error => {
                                // Nếu server lỗi (ví dụ 500 hoặc mất mạng), đảo ngược lại checkbox về trạng thái cũ để tránh sai lệch UI
                                checkboxInput.checked = !checkboxInput.checked;
                                console.error('Lỗi khi cập nhật:', error);
                            });
                    });
                });


                // Delete subtask
                // document.querySelectorAll('.delete-subtask').forEach(btn => {
                //     btn.addEventListener('click', function() {
                //         if (confirm('Delete this subtask?')) {
                //             const subtaskId = this.dataset.subtaskId;
                //             fetch(`/subtasks/${subtaskId}`, {
                //                     method: 'DELETE',
                //                     headers: {
                //                         'Content-Type': 'application/json',
                //                         'X-CSRF-TOKEN': document.querySelector(
                //                             'meta[name="csrf-token"]').content,
                //                     },
                //                 })
                //                 .then(response => response.json())
                //                 .then(data => {
                //                     if (data.success) {
                //                         document.querySelector(`[data-subtask-id="${subtaskId}"]`)
                //                             .remove();
                //                     }
                //                 });
                //         }
                //     });
                // });
            });
        </script>
    @endpush

</x-app-layout>

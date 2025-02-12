<div class="p-4">
    <livewire:customers.tasks.create :$customer/>
    <div class="uppercase font-bold text-slate-600 text-xs mb-2">
        Pending
        [{{ $this->notDoneTasks->count() }}]
    </div>
    <ul class="flex flex-col gap-1 mb-6" wire:sortable="updateTaskOrder">
        @foreach($this->notDoneTasks as $task)
            <li wire:sortable.item="{{ $task->id }}" wire:key="task-not-done{{ $task->id }}">
                <input type="checkbox" id="task-not-done-{{ $task->id }}" value="1" @checked($task->done_at) />
                <label class="cursor-grab" for="task-not-done-{{ $task->id }}"
                       wire:sortable.handle>{{ $task->title }}</label>
                <select>
                    <option>Assigned to: {{ $task->assignedTo?->name }}</option>
                </select>
            </li>
        @endforeach
    </ul>

    <hr class="border-dashed border-gray-700 my-4">

    <div class="uppercase font-bold text-slate-600 text-xs mb-2">
        Done
        [{{ $this->doneTasks->count() }}]
    </div>
    <ul class="flex flex-col gap-1" wire:sortable="updateTaskOrder">
        @foreach($this->doneTasks as $task)
            <li wire:sortable.item="{{ $task->id }}" wire:key="task-done{{ $task->id }}">
                <input type="checkbox" id="task-done-{{ $task->id }}" value="1" @checked($task->done_at) />
                <label lass="cursor-grab" for="task-done-{{ $task->id }}"
                       wire:sortable.handle>{{ $task->title }}</label>
                <select>
                    <option>Assigned to: {{ $task->assignedTo?->name }}</option>
                </select>
            </li>
        @endforeach
    </ul>
</div>

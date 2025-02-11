<div class="p-4">
    <div class="uppercase font-bold text-slate-600 text-xs mb-2">
        Pending
        [{{ $this->notDoneTasks->count() }}]
    </div>
    <ul class="flex flex-col gap-1 mb-6">
        @foreach($this->notDoneTasks as $task)
            <li>
                <input type="checkbox" id="task-{{ $task->id }}" value="1" @checked($task->done_at) />
                <label for="task-{{ $task->id }}">{{ $task->title }}</label>
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
    <ul class="flex flex-col gap-1">
        @foreach($this->doneTasks as $task)
            <li>
                <input type="checkbox" id="task-{{ $task->id }}" value="1" @checked($task->done_at) />
                <label for="task-{{ $task->id }}"><{{ $task->title }}/label>
                <select>
                    <option>Assigned to: {{ $task->assignedTo?->name }}</option>
                </select>
            </li>
        @endforeach
    </ul>
</div>

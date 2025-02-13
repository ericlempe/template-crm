<div class="p-4">
    <livewire:customers.tasks.create :$customer/>
    <div class="uppercase font-bold text-slate-600 text-xs mb-2">
        Pending
        [{{ $this->notDoneTasks->count() }}]
    </div>
    <ul class="flex flex-col gap-1 mb-6" wire:sortable="updateTaskOrder">
        @foreach($this->notDoneTasks as $task)

            <li class="flex justify-between items-center" wire:sortable.item="{{ $task->id }}"
                wire:key="task-not-done{{ $task->id }}">

                <div class="flex items-center gap-2">
                    <x-button
                        class="btn-square btn-ghost btn-sm cursor-grab"
                        tooltip="{{ __('Drag to reorder') }}"
                        icon="o-chevron-up-down"
                        wire:sortable.handle
                    />

                    <x-checkbox
                        :label="$task->title"
                        id="task-done-{{ $task->id }}"
                        wire:click="toggleCheck({{ $task->id  }}, 'done')"
                        class="checkbox-sm"
                        value="1"
                    />

                    <select>
                        <option>Assigned to: {{ $task->assignedTo?->name }}</option>
                    </select>
                </div>
                <x-button
                    wire:click="deleteTask({{ $task->id }})"
                    class="btn-square btn-sm btn-ghost"
                    tooltip="{{ __('Task Delete') }}"
                    spinner
                >
                    <x-icon name="o-trash" class="hover:text-red-500"/>
                </x-button>
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
            <li class="flex justify-between items-center" wire:sortable.item="{{ $task->id }}"
                wire:key="task-done{{ $task->id }}">

                <div class="flex items-center gap-2">
                    <x-button
                        class="btn-square btn-ghost btn-sm cursor-grab"
                        tooltip="{{ __('Drag to reorder') }}"
                        icon="o-chevron-up-down"
                        wire:sortable.handle
                    />
                    <x-checkbox
                        :label="$task->title"
                        id="task-done-{{ $task->id }}"
                        wire:click="toggleCheck({{ $task->id  }}, 'pending')"
                        class="checkbox-sm"
                        value="1"
                        checked
                    />

                    <select>
                        <option>Assigned to: {{ $task->assignedTo?->name }}</option>
                    </select>
                </div>
                <x-button
                    wire:click="deleteTask({{ $task->id }})"
                    class="btn-square btn-sm btn-ghost"
                    tooltip="{{ __('Task Delete') }}"
                    spinner
                >
                    <x-icon name="o-trash" class="hover:text-red-500"/>
                </x-button>
            </li>
        @endforeach
    </ul>
</div>

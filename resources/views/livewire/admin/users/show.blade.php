<div>
    <x-drawer wire:model="modal" class="w-11/12 lg:w-1/3" :title="'Details ' . $user?->name" right>
        <div class="grid grid-col-1 md:grid-cols-2 gap-4">
            @if($user)

                <div class="flex flex-col">
                    <p class="font-bold">Name</p>
                    <p>{{ $user->name }}</p>
                </div>

                <div class="flex flex-col">
                    <p class="font-bold">Email</p>
                    <p>{{ $user->email }}</p>
                </div>

                <div class="flex flex-col">
                    <p class="font-bold">Created at</p>
                    <p>{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="flex flex-col">
                    <p class="font-bold">Updated at</p>
                    <p>{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>

                @if($user->trashed())
                    <div class="flex flex-col">
                        <p class="font-bold">Deleted at</p>
                        <p>{{ $user->deleted_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="flex flex-col">
                        <p class="font-bold">Deleted by</p>
                        <p>{{ $user->deletedBy->name }}</p>
                    </div>
                @endif
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Close" @click="$wire.modal = false"/>
        </x-slot:actions>
    </x-drawer>
</div>

<div>

    <x-header>
        <x-slot name="title">
            {{ __('Customer') . " {$customer->name}" }}
        </x-slot>
        <x-slot name="actions">
            <x-button
                label="Back"
                icon="o-arrow-left"
                :link="route('customers')"
                class="btn-ghost btn-outline btn-sm"
                tooltip-bottom="Go back to the list of customers"
            />
        </x-slot>
    </x-header>

    <div class="grid grid-cols-3 gap-4">

        <div class="flex flex-col p-4 rounded-lg bg-base-300 gap-5 space-y-2 text-base font-normal">
            <div>
                <x-info.header>Personal Info</x-info.header>
                <x-info.data label="Name">{{ $customer->name }}</x-info.data>
                <x-info.data label="Birthday">{{ $customer->birthday }}</x-info.data>
                <x-info.data label="Gender">{{ $customer->gender }}</x-info.data>
            </div>

            <div>
                <x-info.header>Company Info</x-info.header>
                <x-info.data label="Company">{{ $customer->company }}</x-info.data>
                <x-info.data label="Position">{{ $customer->position }}</x-info.data>
            </div>

            <div>
                <x-info.header>Contact Info</x-info.header>
                <x-info.data label="Email">{{ $customer->email }}</x-info.data>
                <x-info.data label="Phone">{{ $customer->phone }}</x-info.data>
            </div>

            <div>
                <x-info.header>Social Media Info</x-info.header>
                <x-info.data label="Linkedin">{{ $customer->linkedin }}</x-info.data>
                <x-info.data label="Facebook">{{ $customer->facebook }}</x-info.data>
                <x-info.data label="Twitter">{{ $customer->twitter }}</x-info.data>
                <x-info.data label="Instagram">{{ $customer->instagram }}</x-info.data>
            </div>

            <div>
                <x-info.header>Address Info</x-info.header>
                <x-info.data label="Address">{{ $customer->address }}</x-info.data>
                <x-info.data label="City">{{ $customer->city }}</x-info.data>
                <x-info.data label="State">{{ $customer->state }}</x-info.data>
                <x-info.data label="Country">{{ $customer->country }}</x-info.data>
                <x-info.data label="Zip">{{ $customer->zip }}</x-info.data>
            </div>

            <div>
                <x-info.header>Record Info</x-info.header>
                <x-info.data label="Created at">{{ $customer->created_at->format('d/m/Y') }}</x-info.data>
                <x-info.data label="Updated at">{{ $customer->updated_at->format('d/m/Y') }}</x-info.data>
            </div>
        </div>

        <div class="col-span-2 bg-base-300">
            <div class="py-2 bg-base-100 rounded-t-lg w-full space-x-4">
                <x-ui.tab :href="route('customers.show', [$customer, 'opportunities'])">
                    Opportunities
                </x-ui.tab>
                <x-ui.tab :href="route('customers.show', [$customer, 'tasks'])">
                    Tasks
                </x-ui.tab>
                <x-ui.tab :href="route('customers.show', [$customer, 'notes'])">
                    Notes
                </x-ui.tab>
            </div>

            <div class="">
                @livewire("customers.$tab.index", ["customer" => $customer])
            </div>
        </div>
    </div>
</div>

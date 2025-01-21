<x-card title="Login">
    <x-form class="space-y-6" wire:submit="submit" no-separator>
        <x-input label="Name" wire:model="name"/>
        <x-input type="email" label="Email" wire:model="email"/>
        <x-input type="password" label="Password" wire:model="password"/>
        <x-input type="password" label="Confirm your password" wire:model="password_confirmation"/>

        <x-slot:actions>
            <x-button type="reset" label="Reset"/>
            <x-button label="Register" class="btn-primary" type="submit" spinner="submit"/>
        </x-slot:actions>
    </x-form>
    <p class="mt-10 text-center text-sm text-gray-400">
        Already have an account?
        <a wire:navigate href="{{ route('login') }}"
           class="font-semibold leading-6 text-indigo-400 hover:text-indigo-300">
            Login
        </a>
    </p>
</x-card>


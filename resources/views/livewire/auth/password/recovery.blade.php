<x-card title="Password Recovery">
    @if($message)
        <x-alert icon="o-check-circle" class="alert-success my-2 text-white">
            {{ $message }}
        </x-alert>
    @endif
    <x-form class="space-y-6" wire:submit="recoveryPassword" no-separator>
        <x-input type="email" label="Email" wire:model="email"/>
        <x-slot:actions>
            <x-button label="Recovery" class="btn-primary w-full" type="submit" spinner="recoveryPassword"/>
        </x-slot:actions>
    </x-form>
    <p class="mt-10 text-center text-sm text-gray-400">
        <a wire:navigate href="{{ route('login') }}"
           class="font-semibold leading-6 text-indigo-400 hover:text-indigo-300">
            Back to Login
        </a>
    </p>
</x-card>


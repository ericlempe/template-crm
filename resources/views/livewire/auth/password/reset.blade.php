<x-card title="Reset Password">

    @if($message = session()->get('status'))
    <x-alert icon="o-exclamation-triangle" class="alert-error my-2">
        {{ $message }}
    </x-alert>
    @endif
    <x-form class="space-y-6" wire:submit="changePassword" no-separator>
        <x-input type="email" label="Email" value="{{ $this->obfuscatedEmail }}" readonly />
        <x-input type="email" label="Email Confirmation" wire:model="email_confirmation" />
        <x-input type="password" label="Password" wire:model="password"/>
        <x-input type="password" label="Password Confirmation" wire:model="password_confirmation"/>
        <x-slot:actions>
            <x-button label="Reset" class="btn-primary w-full" type="submit" spinner="changePassword"/>
        </x-slot:actions>
    </x-form>

   <p class="mt-10 text-center text-sm text-gray-400">
        go back to login
        <a wire:navigate href="{{ route('login') }}"
           class="font-semibold leading-6 text-indigo-400 hover:text-indigo-300">
            Login
        </a>
    </p>
</x-card>

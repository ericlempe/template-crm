<x-card title="Login">

    @if($message = session()->get('status'))
    <x-alert icon="o-exclamation-triangle" class="alert-error my-2">
        {{ $message }}
    </x-alert>
    @endif

    @error('invalidCredentials')
    <x-alert icon="o-exclamation-triangle" class="alert-warning my-2">
        {{ $message }}
    </x-alert>
    @enderror

    @error('rateLimiter')
    <x-alert icon="o-exclamation-triangle" class="bg-red-500 my-2 text-white">
        {{ $message }}
    </x-alert>
    @enderror
    <x-form class="space-y-6" wire:submit="login" no-separator>
        <x-input type="email" label="Email" wire:model="email"/>
        <x-input type="password" label="Password" wire:model="password"/>
        <x-slot:actions>
            <x-button label="Login" class="btn-primary w-full" type="submit" spinner="login"/>
        </x-slot:actions>
    </x-form>

    <p class="mt-5 text-center text-sm text-gray-400">
        <a wire:navigate href="{{ route('password.recovery') }}" class="font-semibold text-indigo-400 hover:text-indigo-300">Forgot password?</a>
    </p>
    <p class="mt-10 text-center text-sm text-gray-400">
        Doesn't have an account?
        <a wire:navigate href="{{ route('auth.register') }}"
           class="font-semibold leading-6 text-indigo-400 hover:text-indigo-300">
            Click here to register
        </a>
    </p>
</x-card>

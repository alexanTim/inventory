<div class="flex flex-col gap-6">
    <div class="flex w-full flex-col items-center text-center gap-3">
<x-app-logo size="lg" />
        <flux:subheading>{{ __('Enter your email and password below to log in') }}</flux:subheading>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit.prevent="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
            />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input
                id="remember"
                type="checkbox"
                wire:model="remember"
                class="h-4 w-4 rounded border-zinc-300 text-primary-600 focus:ring-primary-600 cursor-pointer"
            >
            <label for="remember" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Can\'t login?') }}
        <flux:link href="mailto:admin@gentlewalker.com" class="text-primary-600 dark:text-primary-400 hover:underline">{{ __('Contact your administrator') }}</flux:link>
    </div>
</div>

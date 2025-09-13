<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <div x-data="{
            appearance: localStorage.getItem('flux.appearance') || 'system',
            init() {
                console.log('Appearance component initialized:', this.appearance);
                
                // Ensure the theme is applied on component initialization
                this.applyTheme(this.appearance);
                
                // Listen for system theme changes when system mode is selected
                if (window.matchMedia) {
                    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                    mediaQuery.addEventListener('change', (e) => {
                        if (this.appearance === 'system') {
                            console.log('System theme changed, reapplying system preference');
                            this.applyTheme('system');
                        }
                    });
                }
                
                // Sync with global $flux if available
                if (window.$flux) {
                    window.$flux.appearance = this.appearance;
                }
            },
            setTheme(theme) {
                console.log('Setting theme to:', theme);
                
                // Update local state
                this.appearance = theme;
                
                // Persist to localStorage immediately
                try {
                    localStorage.setItem('flux.appearance', theme);
                    console.log('Theme saved to localStorage:', theme);
                } catch (error) {
                    console.error('Failed to save theme to localStorage:', error);
                }
                
                // Apply theme immediately
                this.applyTheme(theme);
                
                // Update global $flux if available
                if (window.$flux) {
                    window.$flux.appearance = theme;
                    console.log('Updated $flux.appearance:', theme);
                }
                
                // Dispatch custom event for other components
                window.dispatchEvent(new CustomEvent('theme-changed', {
                    detail: { appearance: theme }
                }));
                
                // Also dispatch Alpine event
                this.$dispatch('theme-changed', { appearance: theme });
            },
            applyTheme(theme) {
                console.log('Applying theme:', theme);
                
                // Remove existing classes first
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark');
                
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.body.classList.add('dark');
                    console.log('Applied dark theme');
                } else if (theme === 'light') {
                    // Classes already removed above
                    console.log('Applied light theme');
                } else if (theme === 'system') {
                    const isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        document.body.classList.add('dark');
                        console.log('Applied dark theme (system preference)');
                    } else {
                        console.log('Applied light theme (system preference)');
                    }
                }
                
                // Verify the theme was applied
                const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                console.log('Theme verification - Document is now:', currentTheme);
            }
        }" class="space-y-4">
            <!-- Custom theme buttons that definitely work -->
            <div class="flex rounded-lg border border-zinc-300 dark:border-zinc-600 p-1 bg-zinc-50 dark:bg-zinc-800">
                <button 
                    @click="setTheme('light')"
                    :class="appearance === 'light' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-all duration-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Light
                </button>
                
                <button 
                    @click="setTheme('dark')"
                    :class="appearance === 'dark' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-all duration-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    Dark
                </button>
                
                <button 
                    @click="setTheme('system')"
                    :class="appearance === 'system' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-all duration-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    System
                </button>
            </div>
            
            <!-- Debug info -->
            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-4 p-3 bg-zinc-100 dark:bg-zinc-800 rounded-md">
                <div class="grid grid-cols-1 gap-1">
                    <div><strong>Current setting:</strong> <span x-text="appearance" class="font-mono"></span></div>
                    <div><strong>Document class:</strong> <span x-text="document.documentElement.classList.contains('dark') ? 'dark' : 'light'" class="font-mono"></span></div>
                    <div><strong>System preference:</strong> <span x-text="window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'" class="font-mono"></span></div>
                    <div><strong>localStorage:</strong> <span x-text="localStorage.getItem('flux.appearance') || 'null'" class="font-mono"></span></div>
                </div>
                <div class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">
                    Click buttons to test • Theme persists across refreshes • Check console for logs
                </div>
                <button 
                    @click="window.location.reload()"
                    class="mt-2 px-2 py-1 bg-zinc-200 dark:bg-zinc-600 text-zinc-700 dark:text-zinc-300 rounded text-xs hover:bg-zinc-300 dark:hover:bg-zinc-500 transition-colors"
                >
                    🔄 Test Refresh (Theme should persist)
                </button>
            </div>
        </div>
    </x-settings.layout>
</section>

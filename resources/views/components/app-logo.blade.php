@props(['size' => 'lg'])

@php
    $textSizeByToken = [
        'xs' => 'text-xl',
        'sm' => 'text-2xl',
        'md' => 'text-3xl',
        'lg' => 'text-4xl',
        'xl' => 'text-5xl',
        '2xl' => 'text-6xl',
        '3xl' => 'text-7xl',
    ];
    $emByToken = [
        'xs' => '1.0em',
        'sm' => '1.1em',
        'md' => '1.2em',
        'lg' => '1.3em',
        'xl' => '1.4em',
        '2xl' => '1.6em',
        '3xl' => '1.8em',
    ];

    $textSizeClass = $textSizeByToken[$size] ?? $textSizeByToken['lg'];
    $iconEm = $emByToken[$size] ?? $emByToken['lg'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center justify-center $textSizeClass font-extrabold leading-none"]) }}>
    <span class="lowercase text-zinc-900 dark:text-white" style="font-family: Arial, sans-serif;">
        gentle&nbsp;w
    </span>
    <img src="{{ asset('images/gentle_white.png') }}"
         alt="Logo"
         class="hidden dark:block align-middle mx-1"
         style="height: {{ $iconEm }}; width: {{ $iconEm }}; object-fit: contain; margin: 0em 0em;">
    <img src="{{ asset('images/gentle_dark.png') }}"
         alt="Logo"
         class="block dark:hidden align-middle mx-1"
         style="height: {{ $iconEm }}; width: {{ $iconEm }}; object-fit: contain; margin: 0em 0em;">
    <span class="lowercase text-zinc-900 dark:text-white" style="font-family: Arial, sans-serif;">
        lker
    </span>
</div>

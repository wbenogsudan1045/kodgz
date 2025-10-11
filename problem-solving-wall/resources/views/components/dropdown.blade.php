@props(['align' => 'right', 'width' => '64', 'contentClasses' => 'py-3 px-4 bg-white'])

@php
    $alignmentClasses = match ($align) {
        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
        'top' => 'origin-top',
        default => 'ltr:origin-top-right rtl:origin-top-left end-0',
    };

    $width = match ($width) {
        '48' => 'w-48',
        '64' => 'w-64',
        '80' => 'w-80',
        default => $width,
    };
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <!-- Trigger -->
    <div @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <!-- Dropdown -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-3 {{ $width }} rounded-xl shadow-2xl border border-gray-200 {{ $alignmentClasses }}"
        style="display: none; background-color: #fef9c3;" @click="open = false">

        <!-- Content (notifications or profile items) -->
        <div class="max-h-80 overflow-y-auto rounded-xl {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
<x-app-layout>
    <!-- Full height minus navbar -->
    <div class="flex h-[calc(100vh-4rem)]">
        <x-board-tab :board="$board" :notes="$notes" />
    </div>
</x-app-layout>
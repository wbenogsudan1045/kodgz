@props(['board', 'notes'])

<div class="flex h-full w-full">
    {{-- Sidebar --}}
    <x-note-sidebar :board="$board" />

    {{-- Board Area --}}
    <div id="board-area" class="flex-1 relative m-4 overflow-hidden 
           bg-gradient-to-b from-[#6b4f3a] to-[#4a3628] 
           border-[14px] border-[#c49a6c] 
           rounded-lg shadow-lg">

        @if($board->users->contains(auth()->user()))
            {{-- User is allowed to view notes --}}
            @foreach($notes as $note)
                <div class="note absolute max-w-[200px] p-3 rounded shadow cursor-move select-none text-white break-words whitespace-normal"
                    data-id="{{ $note->id }}" data-title="{{ $note->title ?? 'Untitled' }}" data-content="{{ $note->content }}"
                    data-color="{{ $note->color }}" data-author="{{ $note->user->name ?? 'Unknown' }}"
                    data-author-id="{{ $note->user_id }}" data-attachment="{{ $note->attachment_url }}"
                    style="left: {{ $note->x }}px; top: {{ $note->y }}px; background-color: {{ $note->color }};">
                </div>
            @endforeach

            <x-note-add-button :board="$board" />

        @else
            {{-- Access Denied --}}
            <div class="flex items-center justify-center h-full w-full">
                <div class="bg-red-100 text-red-600 p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold">Access Denied</h2>
                    <p class="text-sm mt-2">You don’t have access to this board. Please join it first.</p>
                </div>
            </div>
        @endif
    </div>

    <x-board-script :board="$board" />
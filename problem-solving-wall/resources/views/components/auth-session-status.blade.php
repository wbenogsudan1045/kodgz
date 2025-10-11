@props(['status'])

@if ($status)
    <div {{ $attributes->merge([
            'class' => 'text-red-600 text-sm ml-4'
        ]) }}>
        {{ $status }}
    </div>
@endif
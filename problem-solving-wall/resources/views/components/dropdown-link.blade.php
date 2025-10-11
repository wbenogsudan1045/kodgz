<a {{ $attributes->merge([
    'class' =>
        'block w-full px-4 py-2 text-start text-sm leading-5 text-black
         hover:bg-green-300 focus:bg-green-300
         focus:outline-none transition duration-150 ease-in-out'
]) }}>
    {{ $slot }}
</a>
@props(['active'])

@php
 = ( ?? false)
    ? 'block w-full rounded-full px-4 py-2 text-sm font-medium text-white transition-colors duration-200'
    : 'block w-full rounded-full px-4 py-2 text-sm font-medium text-white/90 transition-colors duration-200 hover:text-white';
@endphp

<a {{ ->merge(['class' => ]) }}>
    {{  }}
</a>

@props(['active'])

@php
 = ( ?? false)
    ? 'inline-flex items-center rounded-full px-3 py-2 text-sm font-medium text-white transition-colors duration-200'
    : 'inline-flex items-center rounded-full px-3 py-2 text-sm font-medium text-white/80 transition-colors duration-200 hover:text-white';
@endphp

<a {{ ->merge(['class' => ]) }}>
    {{  }}
</a>

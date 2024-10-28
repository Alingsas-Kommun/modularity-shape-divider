<div class="{{ $classes }}">
    {!! $svgCode !!}
</div>
@if ($color !== 'none')
    <style>
        .{{ $instanceClass }} {
            color: var(--color-{{ $color }});
        }
    </style>
@endif

@props(['orderBy', 'field', 'orderByDirection'])

@if ($orderBy === $field && $orderByDirection === 'asc')
    <x-svg.chevron-up />
@elseif ($orderBy === $field && $orderByDirection === 'desc')
    <x-svg.chevron-down />
@endif

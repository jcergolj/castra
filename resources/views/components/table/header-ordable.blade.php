@props(['route' => null, 'orderBy' => null, 'orderByDirection' => 'asc', 'field' => null])

<a href="{{ route($route,request()->merge(['order_by' => $field, 'order_by_direction' => $orderByDirection === 'asc' ? 'desc' : 'asc'])->all()) }}"
    class="text-blue-600 hover:text-blue-900 whitespace-no-wrap">
    <div class="flex">
        <div class="mr-1">
            {{ $slot }}
        </div>
        <div>
            <x-table.order-by :orderBy="$orderBy" :field="$field" :orderByDirection="$orderByDirection" />
        </div>
    </div>
</a>

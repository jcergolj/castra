@props(['route' => null])

<form
    id="bulk_delete"
    action="{{ route($route) }}"
    method="post"
>
    @method('delete')
    @csrf
    <button
        type="submit"
        title="Delete All"
    >
        <x-svg.trash />
    </button>
</form>

@props(['action' => null, 'warningText' => 'Are you sure?'])

<form onsubmit="return confirmSubmission()" id="bulk_delete" action="{{ $action }}" method="post">
    @csrf
    <button type="submit" title="Delete All">
        <x-svg.trash />
    </button>
</form>

@push('scripts')
<script>
    function confirmSubmission(event) {
        event.preventDefault();

        if (confirm('{{ $warningText }}') !== true) {
            return;
        }

        var form = document.getElementById('bulk_delete');
        var formData = new FormData();

        document.getElementsByName('ids[]').forEach(function (id, index) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[' + index + ']';
            input.value = id.value;
            form.appendChild(input);
        });

        form.requestSubmit();
    }
</script>
@endpush

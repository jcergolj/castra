@props(['route' => null, 'warningText' => 'Are you sure?'])

<form id="bulk_delete" action="{{ route('admin.users.index') }}" method="post">
    @method('delete')
    @csrf
    <button
        type="submit"
        title="Delete All"
        @click="warning"
    >
        <x-svg.trash/>
    </button>
</form>

@push('scripts')
    <script>
        function warning(event) {
            event.preventDefault();
            swal({
                title: 'Warning',
                text: '{{ $warningText }}',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirmation) => {
                if (!confirmation) {
                    return swal.close();
                }

                var form = document.getElementById('bulk_delete');
                var formData = new FormData();

                document.getElementsByName('ids[]').forEach(function(id, index) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids['+index+']';
                    input.value = id.value;
                    form.appendChild(input);
                });

                form.requestSubmit();
            });
        }
    </script>
@endpush

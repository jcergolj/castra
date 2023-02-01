@props(['perPage'])

<form id="filters" method="get" action="{{ route('admin.users.index') }}">
    @csrf
    <div class="mt-3 flex flex-col sm:flex-row">
        <div class="flex">
            <div class="relative">
                <x-table.search-select name="per_page">
                    <option @if ($perPage == 10) selected @endif value="10">10
                    </option>
                    <option @if ($perPage == 25) selected @endif value="25">25
                    </option>
                    <option @if ($perPage == 50) selected @endif value="50">50
                    </option>
                </x-table.search-select>
            </div>

            {{ $slot }}
        </div>

        <div class="block relative mt-2 sm:mt-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <x-svg.search viewBox="0 0 24 24" class="fill-current text-gray-500" />
            </span>

            <x-table.search-input />
        </div>

        <x-table.search-button />
    </div>
</form>

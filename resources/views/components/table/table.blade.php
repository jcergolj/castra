<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
    <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
        <x-error field="ids" />
        <table
            class="min-w-full leading-normal"
            x-data="{selectAll: false}"
            x-cloak
        >
            <thead>
                <tr x-show="selectAll">
                    <th
                        class="pl-4 pt-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                        colspan="6"
                    >
                        <x-table.delete route="admin.bulk.users.destroy" />
                    </th>
                </tr>
                <tr>
                    <x-table.th>
                        <input
                            @change="
                                selectAll = !selectAll;
                                document.getElementsByName('ids[]').forEach(checkbox => checkbox.checked = selectAll);
                            "
                            type="checkbox"
                        />
                    </x-table.th>

                    {{ $thead }}
                </tr>

            </thead>
            <tbody>
                {{ $tbody }}
            </tbody>
        </table>
        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
</div>

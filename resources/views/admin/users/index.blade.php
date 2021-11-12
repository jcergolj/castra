<x-layouts.auth>
    <h3 class="text-gray-700 text-3xl font-medium">Users</h3>

    <div class="mt-8">

        <div class="mt-6">
            <x-table.filters :perPage="$per_page">
                <div class="relative">
                    <x-table.search-select name="role">
                        <option value="">All</option>
                        <option @if (request()->role == \App\Models\User::ADMIN) selected @endif value="{{ \App\Models\User::ADMIN }}">Admin</option>
                        <option @if (request()->role == \App\Models\User::MEMBER) selected @endif value="{{ \App\Models\User::MEMBER }}">Member</option>
                    </x-table.search-select>
                </div>
            </x-table.filters>
            <x-table.table :items="$users">
                <x-slot name="thead">
                    <x-table.th>
                        <x-table.header-ordable route="admin.users.index" :orderBy="$order_by" :orderByDirection="$order_by_direction" field="email">
                            Email
                        </x-table.header-ordable>
                    </x-table.th>
                    <x-table.th>
                        <x-table.header-ordable route="admin.users.index" :orderBy="$order_by" :orderByDirection="$order_by_direction" field="role">
                            Role
                        </x-table.header-ordable>
                    </x-table.th>
                    <x-table.th>
                        <x-table.header-ordable route="admin.users.index" :orderBy="$order_by" :orderByDirection="$order_by_direction" field="created_at">
                            Created
                        </x-table.header-ordable>
                    </x-table.th>
                    <x-table.th>Edit</x-table.th>
                    <x-table.th>Delete</x-table.th>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($users as $user)
                        <tr>
                            <x-table.td>
                                <input x-ref="item_checkbox" type="checkbox" name="ids[]" value="{{ $user->id }}" />
                            </x-table.td>
                            <x-table.td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-full h-full rounded-full" src="{{ $user->profileImageFile }}"/>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-gray-900 whitespace-no-wrap">{{ $user->role }}</p>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-gray-900 whitespace-no-wrap">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </x-table.td>
                            <x-table.td>
                            </x-table.td>
                            <x-table.td>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="6">
                                There is no users.
                            </x-table.td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table.table>
        </div>
    </div>
</x-layouts.auth>

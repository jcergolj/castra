<x-layouts.auth>
    <h3 class="text-gray-700 text-3xl font-medium">Activites</h3>

    <div class="mt-8">

        <div class="mt-6">
            <x-table.filters :perPage="$per_page" route="activities.index">
                <div class="relative">
                    <x-table.search-select name="activity_event">
                        <option value="">All</option>
                        @foreach (\App\Enums\ActivityEvents::cases() as $activityEvent)
                            <option @if (request()->activity_event === $activityEvent->value) selected @endif
                                value="{{ $activityEvent->value }}">
                                {{ $activityEvent->value }}
                            </option>
                        @endforeach
                    </x-table.search-select>
                </div>
            </x-table.filters>
            <x-table.table :items="$activities">
                <x-slot name="thead">
                    <x-table.th>
                        <x-table.header-ordable route="activities.index" :orderBy="$order_by"
                            :orderByDirection="$order_by_direction" field="event">
                            Event
                        </x-table.header-ordable>
                    </x-table.th>
                    <x-table.th>
                        Who did it
                    </x-table.th>
                    <x-table.th>
                        Affected Item
                    </x-table.th>
                    <x-table.th>
                        <x-table.header-ordable route="activities.index" :orderBy="$order_by"
                            :orderByDirection="$order_by_direction" field="created_at">
                            Created
                        </x-table.header-ordable>
                    </x-table.th>
                    <x-table.th>
                        Restore deleted item
                    </x-table.th>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($activities as $activity)
                        <tr>
                            <x-table.td>
                                {{ $activity->event->value }}
                            </x-table.td>
                            <x-table.td>
                                {{ $activity->causer->email }}
                            </x-table.td>
                            <x-table.td>
                                {{ $activity->properties['subject']['email'] }}
                            </x-table.td>
                            <x-table.td>
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ $activity->created_at->format('d/m/Y H:i') }}</p>
                            </x-table.td>
                            <x-table.td>
                                @if ($activity->event === \App\Enums\ActivityEvents::Deleted)
                                    <form action="{{ route('restored-item.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $activity->id }}">
                                        <input type="hidden" name="subject_id" value="{{ $activity->subject_id }}">
                                        <input type="hidden" name="subject_type" value="{{ $activity->subject_type }}">
                                        <button type="submit" title="Restore Item">
                                            <x-svg.undo />
                                        </button>
                                    </form>
                                @endif
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="5">
                                There is no activites.
                            </x-table.td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table.table>
        </div>
    </div>
</x-layouts.auth>

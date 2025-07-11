<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blood Samples') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Add Blood Sample Button -->
            <div class="flex justify-start mb-4">
                <a href="{{ route('nurse.samples.edit', ['id' => null]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 ms-4">
                    Add Blood Sample
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Blood Type</th>
                                <th class="px-4 py-2 text-left">Volume (ml)</th>
                                <th class="px-4 py-2 text-left">Collected On</th>
                                <th class="px-4 py-2 text-left">Expires On</th>
                                <th class="px-4 py-2 text-left">Nurse</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Notes</th>
                                <th class="px-4 py-2 text-left w-1/4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($samples as $sample)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2">{{ $sample->id }}</td>
                                    <td class="px-4 py-2">{{ $sample->blood_type }}{{ $sample->rh_factor }}</td>
                                    <td class="px-4 py-2">{{ $sample->volume_ml }}</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($sample->collection_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($sample->expiration_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-2">{{ $sample->nurse->name }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium uppercase
                                            @if($sample->status === 'available') bg-green-100 text-green-800
                                            @elseif($sample->status === 'requested') bg-yellow-100 text-yellow-800
                                            @elseif($sample->status === 'used') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $sample->status }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-2">{{ Str::limit($sample->notes, 30) ?? '—' }}</td>
                                    <td class="px-4 py-2 space-x-2 uppercase">
                                        <a href="{{ route('nurse.samples.view', ['id' => $sample->id]) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold rounded-md hover:bg-gray-700">
                                            View
                                        </a>

                                        @if($sample->status !== 'used')
                                            <a href="{{ route('nurse.samples.edit', ['id' => $sample->id]) }}"
                                                class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-xs font-semibold rounded-md hover:bg-green-900">
                                                Edit
                                            </a>
                                            <a href="{{ route('nurse.samples.delete', ['id' => $sample->id]) }}"
                                                onclick="return confirm('Are you sure you want to delete this sample?')"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-500">
                                                Delete
                                            </a>
                                        @else
                                            <span
                                                class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-xs font-semibold rounded-md opacity-50 cursor-not-allowed">
                                                Edit
                                            </span>
                                            <span
                                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded-md opacity-50 cursor-not-allowed">
                                                Delete
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                        No blood samples found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>
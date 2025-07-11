<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blood Samples Available') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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
                                <th class="px-4 py-2 text-left">From</th>
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
                                    <td class="px-4 py-2">{{ $sample->facility->name }}</td>
                                    <td class="px-4 py-2">{{ Str::limit($sample->notes, 30) ?? '—' }}</td>
                                    <td class="px-4 py-2 uppercase">
                                        <a href="{{ route('doctor.samples.view', ['id' => $sample->id]) }}"
                                            class="inline-flex items-center px-4 py-2 bg-green-900 text-white text-xs font-semibold rounded-md hover:bg-green-800 focus:ring-2 focus:ring-green-300 active:bg-green-950">
                                            View & Request Sample
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                        No blood samples available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
</x-app-layout>
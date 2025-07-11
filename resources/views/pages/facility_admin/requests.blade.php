<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sample Requests') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-2 text-left">Request ID</th>
                                <th class="px-4 py-2 text-left">Sample ID</th>
                                <th class="px-4 py-2 text-left">Blood Type</th>
                                <th class="px-4 py-2 text-left">Collected On</th>
                                <th class="px-4 py-2 text-left">Sent On</th>
                                <th class="px-4 py-2 text-left">To</th>
                                <th class="px-4 py-2 text-left">Notes</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($requests as $request)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 font-medium text-gray-800">
                                        {{ $request->id }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        #{{ $request->bloodSample->id }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $request->bloodSample->blood_type }}{{ $request->bloodSample->rh_factor }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ \Carbon\Carbon::parse($request->bloodSample->collection_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-2">{{ $request->bloodSample->nurse->name }} ({{ $request->bloodSample->facility->name }})</td>
                                    <td class="px-4 py-2" title="{{ $request->notes }}">
                                        {{ Str::limit($request->notes, 50) ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        <span class="inline-flex items-center px-2 py-1 uppercase rounded text-xs font-medium
                                            @if($request->status === 'pending')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'approved')
                                                bg-green-100 text-green-800
                                            @elseif($request->status === 'denied')
                                                bg-red-100 text-red-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                            ">{{ $request->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                        No sample requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
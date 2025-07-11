<x-app-layout>
    <div class="py-6">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
        </x-slot>

        <!-- Facility Info -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-6 flex items-center">
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">
                        Your Facility: {{ Auth::user()->facility->name }} - {{ Auth::user()->facility->code }}
                    </h3>
                    <p class="text-sm">{{ Auth::user()->facility->address }}, {{ Auth::user()->facility->city }} -
                        {{ Auth::user()->facility->country }}
                    </p>
                    <p class="text-sm">
                        Info Email:
                        <a href="mailto:{{ Auth::user()->facility->email }}"
                            class="underline">{{ Auth::user()->facility->email }}</a>,
                        Phone:
                        <a href="tel:{{ Auth::user()->facility->phone }}"
                            class="underline">{{ Auth::user()->facility->phone }}</a>
                    </p>
                </div>
            </div>

            <!-- Sample Requests cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Sample Requests Approved at the Facility</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $approvedRequests }}</p>
                </div>

                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Sample Requests Pending at the Facility</h3>
                    <p class="text-3xl font-bold text-yellow-500">{{ $pendingRequests }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mt-4">
                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Doctors registered with the Facility</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $facilityDoctors }}</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
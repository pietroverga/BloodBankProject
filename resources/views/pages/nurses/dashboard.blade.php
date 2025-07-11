<x-app-layout>
    <div class="py-6">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
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

            <!-- Blood cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Available Facility Blood Samples</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $samplesNr }}</p>
                </div>

                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Expired Samples</h3>
                    <p class="text-3xl font-bold text-yellow-500">{{ $expiredSamples }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- Blood type chart -->
                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Available Blood Type Distribution</h3>
                    @if($samplesNr > 0)
                        <div class="flex items-center gap-6 px-8">
                            <div class="flex-shrink-0">
                                <canvas id="bloodTypeChart" width="250" height="250"></canvas>
                            </div>
                            <div id="customLegend" class="flex-1 space-y-2">
                            </div>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const bloodTypeCtx = document.getElementById('bloodTypeChart').getContext('2d');
                            const labels = {!! json_encode($bloodTypeStats->keys()) !!};
                            const data = {!! json_encode($bloodTypeStats->values()) !!};
                            const colors = [
                                "#BC4846",
                                "#3A5A84",
                                "#2A6B5E",
                                "#8C6B10",
                                "#5F4B8B",
                                "#8B3A63",
                                "#A65629",
                                "#256D8A"
                            ];

                            const chart = new Chart(bloodTypeCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Samples',
                                        data: data,
                                        backgroundColor: colors,
                                        hoverOffset: 4
                                    }]
                                },
                                options: {
                                    responsive: false,
                                    maintainAspectRatio: true,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    }
                                }
                            });

                            const legendContainer = document.getElementById('customLegend');
                            const total = data.reduce((sum, value) => sum + value, 0);
                            labels.forEach((label, index) => {
                                const value = data[index];
                                const percentage = ((value / total) * 100).toFixed(1);

                                const legendItem = document.createElement('div');
                                legendItem.className = 'flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors';
                                legendItem.innerHTML = `<div class="flex items-center gap-3">
                                                                <div class="w-4 h-4 rounded-full" style="background-color: ${colors[index]}"></div>
                                                                <span class="text-sm font-medium text-gray-700">${label}</span>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="text-sm font-semibold text-gray-900">${value}</div>
                                                                <div class="text-xs text-gray-500">${percentage}%</div>
                                                            </div>
                                                        `;
                                legendContainer.appendChild(legendItem);
                            });
                        </script>
                    @else
                        <div class="h-64 flex items-center justify-center text-gray-400 text-sm">
                            No blood sample data available.
                        </div>
                    @endif
                </div>
                
                <!-- Latest pending sample requests -->
                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Latest Pending Sample Requests</h3>
                    @if ($latestRequests->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach ($latestRequests as $request)
                                <li class="py-3">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-800 font-medium">
                                                Request ID {{ $request->id }} - Sample #{{ $request->bloodSample->id }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Blood Type:
                                                <strong>{{ $request->bloodSample->blood_type }}{{ $request->bloodSample->rh_factor }}</strong>
                                                · Collected on
                                                {{ \Carbon\Carbon::parse($request->bloodSample->collection_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="h-64 flex items-center justify-center text-gray-400 text-sm">
                            No pending sample requests found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
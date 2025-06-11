<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Total Athletes</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalAthletes }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Total Competitions</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $competitionsByYear->sum('total') }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Total Registrations</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $registrationsByYear->sum('total') }}</p>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Athletes by Region -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Athletes by Region</h3>
                    <canvas id="regionChart" height="300"></canvas>
                </div>

                <!-- Athletes by Gender -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Athletes by Gender</h3>
                    <canvas id="genderChart" height="300"></canvas>
                </div>

                <!-- Registrations by Day -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Registrations by Day</h3>
                    <canvas id="registrationsChart" height="300"></canvas>
                </div>

                <!-- Registrations by Category -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Registrations by Category</h3>
                    <canvas id="categoryChart" height="300"></canvas>
                </div>

                <!-- Top Regions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Top 5 Regions by Registrations</h3>
                    <canvas id="topRegionsChart" height="300"></canvas>
                </div>

                <!-- Average Entry Totals -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Average Entry Totals by Category</h3>
                    <canvas id="entryTotalsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Athletes by Region Chart
            new Chart(document.getElementById('regionChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($athletesByRegion->pluck('region')) !!},
                    datasets: [{
                        label: 'Number of Athletes',
                        data: {!! json_encode($athletesByRegion->pluck('total')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Athletes by Gender Chart
            new Chart(document.getElementById('genderChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($athletesByGender->pluck('gender')) !!},
                    datasets: [{
                        data: {!! json_encode($athletesByGender->pluck('total')) !!},
                        backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)']
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Registrations by Day Chart
            new Chart(document.getElementById('registrationsChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($registrationsByDay->pluck('date')) !!},
                    datasets: [{
                        label: 'Number of Registrations',
                        data: {!! json_encode($registrationsByDay->pluck('total')) !!},
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'MMM d, yyyy'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });

            // Registrations by Category Chart
            new Chart(document.getElementById('categoryChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($registrationsByCategory->pluck('category')) !!},
                    datasets: [{
                        label: 'Number of Registrations',
                        data: {!! json_encode($registrationsByCategory->pluck('total')) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Top Regions Chart
            new Chart(document.getElementById('topRegionsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topRegions->pluck('region')) !!},
                    datasets: [{
                        label: 'Number of Registrations',
                        data: {!! json_encode($topRegions->pluck('total')) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Average Entry Totals Chart
            new Chart(document.getElementById('entryTotalsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($avgEntryTotals->pluck('category')) !!},
                    datasets: [{
                        label: 'Average Entry Total',
                        data: {!! json_encode($avgEntryTotals->pluck('average')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 
@extends('layouts.field')

@section('title', auth()->user()->role === 'supply_head' ? 'Supply Head Dashboard' : 'Field Officer Dashboard')

@section('content')
    <div class="space-y-6">
        @if($user->role === 'supply_head')
            {{-- Supply Head: property submission counters --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $shStats = [
                        ['label' => 'Total', 'value' => $counters['total'] ?? 0, 'cls' => 'bg-gray-100 text-gray-700', 'b' => 'border-gray-200', 'status' => ''],
                        ['label' => 'Pending Review', 'value' => $counters['pending'] ?? 0, 'cls' => 'bg-blue-50 text-blue-700', 'b' => 'border-blue-100', 'status' => 'submitted'],
                        ['label' => 'Verified', 'value' => $counters['verified'] ?? 0, 'cls' => 'bg-green-50 text-green-700', 'b' => 'border-green-100', 'status' => 'verified'],
                        ['label' => 'Rejected', 'value' => $counters['rejected'] ?? 0, 'cls' => 'bg-red-50 text-red-700', 'b' => 'border-red-100', 'status' => 'rejected'],
                        ['label' => 'Recheck', 'value' => $counters['recheck'] ?? 0, 'cls' => 'bg-orange-50 text-orange-700', 'b' => 'border-orange-200', 'status' => 'recheck'],
                    ];
                @endphp
                @foreach($shStats as $stat)
                    <a href="{{ route('supplyhead.properties.index', $stat['status'] ? ['status' => $stat['status']] : []) }}"
                        class="bg-white rounded-xl border {{ $stat['b'] }} p-4 text-center shadow-sm hover:shadow transition-shadow block">
                        <div class="text-2xl font-heading font-bold {{ $stat['cls'] }} rounded-lg py-1">{{ $stat['value'] }}</div>
                        <div class="text-xs text-gray-500 mt-1 font-medium">{{ $stat['label'] }}</div>
                    </a>
                @endforeach
            </div>

            {{-- Team Performance Chart (Supply Head only) --}}
            @if($user->role === 'supply_head' && isset($officerStats) && $officerStats->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-heading font-semibold text-zendo-navy">Team Performance Overview</h3>
                        <a href="{{ route('supplyhead.properties.index') }}" class="text-sm text-blue-600 hover:underline">Manage
                            Submissions →</a>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Chart --}}
                        <div class="relative">
                            <canvas id="teamPerformanceChart" width="400" height="300"></canvas>
                        </div>

                        {{-- Officer Details --}}
                        <div class="space-y-3">
                            @foreach($officerStats as $officer)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900 truncate">{{ $officer['name'] }}</h4>
                                        <span class="text-sm font-bold text-gray-600">{{ $officer['total'] }} entries</span>
                                    </div>
                                    <div class="grid grid-cols-5 gap-1 text-xs">
                                        @if($officer['draft'] > 0)
                                            <div class="bg-gray-300 text-gray-800 px-2 py-1 rounded text-center">
                                                D: {{ $officer['draft'] }}
                                            </div>
                                        @endif
                                        @if($officer['submitted'] > 0)
                                            <div class="bg-blue-300 text-blue-800 px-2 py-1 rounded text-center">
                                                P: {{ $officer['submitted'] }}
                                            </div>
                                        @endif
                                        @if($officer['verified'] > 0)
                                            <div class="bg-green-300 text-green-800 px-2 py-1 rounded text-center">
                                                V: {{ $officer['verified'] }}
                                            </div>
                                        @endif
                                        @if($officer['rejected'] > 0)
                                            <div class="bg-red-300 text-red-800 px-2 py-1 rounded text-center">
                                                R: {{ $officer['rejected'] }}
                                            </div>
                                        @endif
                                        @if($officer['recheck'] > 0)
                                            <div class="bg-orange-300 text-orange-800 px-2 py-1 rounded text-center">
                                                RC: {{ $officer['recheck'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Chart.js Script --}}
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const ctx = document.getElementById('teamPerformanceChart').getContext('2d');

                        const officers = @json($officerStats->pluck('name'));
                        const drafts = @json($officerStats->pluck('draft'));
                        const submitted = @json($officerStats->pluck('submitted'));
                        const verified = @json($officerStats->pluck('verified'));
                        const rejected = @json($officerStats->pluck('rejected'));
                        const recheck = @json($officerStats->pluck('recheck'));

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: officers,
                                datasets: [
                                    {
                                        label: 'Draft',
                                        data: drafts,
                                        backgroundColor: '#9CA3AF',
                                        borderColor: '#6B7280',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Pending Review',
                                        data: submitted,
                                        backgroundColor: '#60A5FA',
                                        borderColor: '#3B82F6',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Verified',
                                        data: verified,
                                        backgroundColor: '#34D399',
                                        borderColor: '#10B981',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Rejected',
                                        data: rejected,
                                        backgroundColor: '#F87171',
                                        borderColor: '#EF4444',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Recheck',
                                        data: recheck,
                                        backgroundColor: '#FBBF24',
                                        borderColor: '#F59E0B',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Entry Status by Field Officer'
                                    },
                                    legend: {
                                        position: 'bottom'
                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                    },
                                    y: {
                                        stacked: true,
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endif

        @elseif($user->role === 'field_officer')
            {{-- Field Officer: my property counters --}}
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                @php
                    $foStats = [
                        ['label' => 'Total', 'value' => $counters['total'] ?? 0, 'cls' => 'bg-gray-100 text-gray-700', 'b' => 'border-gray-200', 'status' => ''],
                        ['label' => 'Draft', 'value' => $counters['draft'] ?? 0, 'cls' => 'bg-gray-50 text-gray-600', 'b' => 'border-gray-200', 'status' => 'draft'],
                        ['label' => 'Submitted', 'value' => $counters['submitted'] ?? 0, 'cls' => 'bg-blue-50 text-blue-700', 'b' => 'border-blue-100', 'status' => 'submitted'],
                        ['label' => 'Verified', 'value' => $counters['verified'] ?? 0, 'cls' => 'bg-green-50 text-green-700', 'b' => 'border-green-100', 'status' => 'verified'],
                        ['label' => 'Recheck', 'value' => $counters['recheck'] ?? 0, 'cls' => 'bg-orange-50 text-orange-700', 'b' => 'border-orange-200', 'status' => 'recheck'],
                        ['label' => 'Rejected', 'value' => $counters['rejected'] ?? 0, 'cls' => 'bg-red-50 text-red-700', 'b' => 'border-red-100', 'status' => 'rejected'],
                    ];
                @endphp
                @foreach($foStats as $stat)
                    <a href="{{ route('field.properties.index', $stat['status'] ? ['status' => $stat['status']] : []) }}"
                        class="bg-white rounded-xl border {{ $stat['b'] }} p-4 text-center shadow-sm hover:shadow transition-shadow block">
                        <div class="text-2xl font-heading font-bold {{ $stat['cls'] }} rounded-lg py-1">{{ $stat['value'] }}</div>
                        <div class="text-xs text-gray-500 mt-1 font-medium">{{ $stat['label'] }}</div>
                    </a>
                @endforeach
            </div>

            @if(($counters['recheck'] ?? 0) > 0)
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm font-medium text-orange-800">
                            {{ $counters['recheck'] }} {{ Str::plural('entry', $counters['recheck']) }}
                            need{{ $counters['recheck'] === 1 ? 's' : '' }} your attention
                        </p>
                    </div>
                    <a href="{{ route('field.properties.index') }}"
                        class="text-sm text-orange-700 font-semibold hover:underline">View →</a>
                </div>
            @endif
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-semibold text-zendo-navy">My Properties</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Submit and track your property listings</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('field.properties.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                        + New Entry
                    </a>
                </div>
            </div>
            {{-- Recent Entries Table --}}
            @if($recentEntries->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-heading font-semibold text-zendo-navy">Recent Entries (5)</h3>
                        <a href="{{ route('field.properties.index') }}" class="text-sm text-blue-600 hover:underline">View All →</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($recentEntries as $entry)
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="text-sm font-mono font-semibold text-zendo-navy">{{ $entry->code }}</span>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">
                                            {{ $entry->status_label }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <span>{{ $entry->facility_type ?? 'N/A' }}</span>
                                        <span>•</span>
                                        <span>{{ $entry->nearest_city ?? 'N/A' }}</span>
                                        @if($entry->submitted_at)
                                            <span>•</span>
                                            <span>{{ $entry->submitted_at->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(in_array($entry->status, ['draft', 'recheck']))
                                        <a href="{{ route('field.properties.edit', $entry) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-zendo-gold text-white text-xs font-semibold rounded-lg hover:bg-opacity-90 transition-all">
                                            Edit
                                        </a>
                                    @endif
                                    <a href="{{ route('field.properties.show', $entry) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition-all">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
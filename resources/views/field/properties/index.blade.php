@extends('layouts.field')
@section('title', 'My Property Entries')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">My Property Entries</h2>
                <p class="text-gray-500 text-sm mt-1">Track and manage your property submissions</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('field.properties.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                    + New Entry
                </a>
            </div>
        </div>

        {{-- Stat Cards (always full dataset counts) --}}
        <div class="grid grid-cols-6 gap-4">
            @php
                $stats = [
                    ['label' => 'Total', 'value' => $counters['total'], 'cls' => 'bg-gray-100 text-gray-700', 'b' => 'border-gray-200'],
                    ['label' => 'Draft', 'value' => $counters['draft'] ?? 0, 'cls' => 'bg-gray-50 text-gray-500', 'b' => 'border-gray-200'],
                    ['label' => 'Submitted', 'value' => $counters['submitted'], 'cls' => 'bg-blue-50 text-blue-700', 'b' => 'border-blue-100'],
                    ['label' => 'Verified', 'value' => $counters['verified'], 'cls' => 'bg-green-50 text-green-700', 'b' => 'border-green-100'],
                    ['label' => 'Recheck', 'value' => $counters['recheck'], 'cls' => 'bg-orange-50 text-orange-700', 'b' => 'border-orange-200'],
                    ['label' => 'Rejected', 'value' => $counters['rejected'], 'cls' => 'bg-red-50 text-red-700', 'b' => 'border-red-100'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white rounded-xl border {{ $stat['b'] }} p-4 text-center shadow-sm">
                    <div class="text-2xl font-heading font-bold {{ $stat['cls'] }} rounded-lg py-1">
                        {{ $stat['value'] }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">
                        {{ $stat['label'] }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}</div>
        @endif

        {{-- Entries Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($entries->isEmpty())
                <div class="p-12 text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium mb-2">No entries found</p>
                    <a href="{{ route('field.properties.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all">
                        + Create First Entry
                    </a>
                </div>
            @else
                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sr
                                    No.</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    City</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Code</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Facility Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Updated</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($entries as $entry)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3 text-sm text-gray-500">
                                        {{ $loop->iteration + ($entries->currentPage() - 1) * $entries->perPage() }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $entry->nearest_city ?? '—' }}</td>
                                    <td class="px-5 py-3 text-sm font-mono font-medium text-zendo-navy">{{ $entry->code }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $entry->facility_type ?? '—' }}</td>
                                    <td class="px-5 py-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">
                                            {{ $entry->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-500">{{ $entry->updated_at->format('d M Y') }}</td>
                                    <td class="px-5 py-3 text-right space-x-3">
                                        @if($entry->isEditable())
                                            <a href="{{ route('field.properties.edit', $entry) }}"
                                                class="text-sm font-medium {{ $entry->status === 'draft' ? 'text-gray-600 hover:text-gray-800' : ($entry->status === 'rejected' ? 'text-orange-600 hover:text-orange-800' : 'text-blue-600 hover:text-blue-800') }}">
                                                {{ $entry->status === 'draft' ? 'Continue' : ($entry->status === 'rejected' ? 'Re-edit' : 'Edit') }}
                                            </a>
                                        @endif
                                        @if(!in_array($entry->status, ['submitted', 'verified', 'rejected']))
                                            <a href="{{ route('field.properties.show', $entry) }}"
                                                class="text-gray-600 hover:text-gray-800 text-sm font-medium">View</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($entries as $entry)
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-zendo-navy rounded-full">{{ $loop->iteration + ($entries->currentPage() - 1) * $entries->perPage() }}</span>
                                        <span class="text-sm font-mono font-semibold text-zendo-navy">{{ $entry->code }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $entry->nearest_city ?? '—' }} •
                                        {{ $entry->facility_type ?? '—' }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">
                                    {{ $entry->status_label }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-400">{{ $entry->updated_at->format('d M Y') }}</span>
                                <div class="flex gap-3">
                                    @if($entry->isEditable())
                                        <a href="{{ route('field.properties.edit', $entry) }}"
                                            class="text-sm font-medium {{ $entry->status === 'draft' ? 'text-gray-600' : ($entry->status === 'rejected' ? 'text-orange-600' : 'text-blue-600') }}">
                                            {{ $entry->status === 'draft' ? 'Continue' : ($entry->status === 'rejected' ? 'Re-edit' : 'Edit') }}
                                        </a>
                                    @endif
                                    @if(!in_array($entry->status, ['submitted', 'verified', 'rejected']))
                                        <a href="{{ route('field.properties.show', $entry) }}"
                                            class="text-sm text-gray-600 font-medium">View</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if($entries->hasPages())
            <div>{{ $entries->links() }}</div>
        @endif

    </div>
@endsection
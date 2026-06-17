@extends('layouts.field')
@section('title', 'Property Submissions')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Property Submissions</h2>
            <p class="text-gray-500 text-sm mt-1">Review and take action on field officer submissions</p>
        </div>
        <a href="{{ route('field.dashboard') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @php
            $stats = [
                ['label'=>'Total','value'=>$counters['total'],'cls'=>'bg-gray-100 text-gray-700','b'=>'border-gray-200','status'=>''],
                ['label'=>'Pending Review','value'=>$counters['pending'],'cls'=>'bg-blue-50 text-blue-700','b'=>'border-blue-100','status'=>'submitted'],
                ['label'=>'Verified','value'=>$counters['verified'],'cls'=>'bg-green-50 text-green-700','b'=>'border-green-100','status'=>'verified'],
                ['label'=>'Rejected','value'=>$counters['rejected'],'cls'=>'bg-red-50 text-red-700','b'=>'border-red-100','status'=>'rejected'],
                ['label'=>'Recheck','value'=>$counters['recheck'],'cls'=>'bg-orange-50 text-orange-700','b'=>'border-orange-200','status'=>'recheck'],
            ];
        @endphp
        @foreach($stats as $stat)
            <a href="{{ route('supplyhead.properties.index', $stat['status'] ? ['status'=>$stat['status']] : []) }}"
                class="bg-white rounded-xl border {{ $stat['b'] }} p-4 text-center shadow-sm hover:shadow transition-shadow block">
                <div class="text-2xl font-heading font-bold {{ $stat['cls'] }} rounded-lg py-1">{{ $stat['value'] }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">{{ $stat['label'] }}</div>
            </a>
        @endforeach
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Enhanced Filters --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code, city, facility, officer..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            
            <select name="field_officer" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                <option value="">All Officers</option>
                @foreach($fieldOfficers as $officer)
                    <option value="{{ $officer->id }}" {{ request('field_officer') == $officer->id ? 'selected' : '' }}>
                        {{ $officer->name }}
                    </option>
                @endforeach
            </select>
            
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                <option value="">All Status</option>
                @foreach(['submitted'=>'Pending Review','verified'=>'Verified','rejected'=>'Rejected','recheck'=>'Recheck'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all">Filter</button>
                <a href="{{ route('supplyhead.properties.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all text-center">Clear</a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($entries->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium">No entries found</p>
            </div>
        @else
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Officer</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Facility Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">City</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($entries as $entry)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 text-sm font-mono font-medium text-zendo-navy">{{ $entry->code }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $entry->fieldOfficer?->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $entry->facility_type ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $entry->nearest_city ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">{{ $entry->status_label }}</span>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $entry->submitted_at?->format('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('supplyhead.properties.show', $entry) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($entries as $entry)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <span class="text-sm font-mono font-semibold text-zendo-navy">{{ $entry->code }}</span>
                                <p class="text-xs text-gray-500">{{ $entry->fieldOfficer?->name }} &bull; {{ $entry->facility_type ?? '—' }} &bull; {{ $entry->nearest_city ?? '—' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">{{ $entry->status_label }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-400">{{ $entry->submitted_at?->format('d M Y') ?? '—' }}</span>
                            <a href="{{ route('supplyhead.properties.show', $entry) }}" class="text-sm text-blue-600 font-medium">Review</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($entries->hasPages())
        <div>{{ $entries->links() }}</div>
    @endif

</div>
@endsection

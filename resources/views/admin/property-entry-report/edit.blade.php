@extends('layouts.admin')
@section('title', 'Edit Property Entry — ' . $entry->code . ' - ZendoIndia Admin')
@section('page-title', 'Edit Property Entry')
@section('page-description', 'Admin full edit access for property entry ' . $entry->code)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <div>
            <div class="flex items-center flex-wrap gap-2 mb-1">
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Edit Entry: {{ $entry->code }}</h2>
                @php
                    $badge = match($entry->status) {
                        'draft'     => 'bg-gray-100 text-gray-700',
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'verified'  => 'bg-green-100 text-green-800',
                        'recheck'   => 'bg-orange-100 text-orange-700',
                        'rejected'  => 'bg-red-100 text-red-800',
                        default     => 'bg-gray-100 text-gray-600',
                    };
                    $label = match($entry->status) {
                        'draft'     => 'Draft',
                        'submitted' => 'Under Review',
                        'verified'  => 'Verified',
                        'recheck'   => 'Needs Recheck',
                        'rejected'  => 'Rejected',
                        default     => ucfirst($entry->status),
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">{{ $label }}</span>

                @php
                    $adminBadge = match($entry->admin_status) {
                        'approved' => 'bg-emerald-100 text-emerald-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default    => 'bg-amber-100 text-amber-800',
                    };
                    $adminLabel = match($entry->admin_status) {
                        'approved' => 'Admin Approved',
                        'rejected' => 'Admin Rejected',
                        default    => 'Admin Pending',
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adminBadge }}">{{ $adminLabel }}</span>
            </div>
            <p class="text-xs text-gray-500">
                Supply Head: <span class="font-medium">{{ $entry->supplyHead?->name ?? '—' }}</span> &bull;
                Officer: <span class="font-medium">{{ $entry->fieldOfficer?->name ?? '—' }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.property-entry-report.show', $entry) }}"
                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View Entry
            </a>
            <a href="{{ route('admin.property-entry-report.index') }}"
                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:text-zendo-navy transition-colors">
                Back to Report
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Admin Controls & Main Form --}}
    <form method="POST" action="{{ route('admin.property-entry-report.update', $entry) }}" enctype="multipart/form-data" x-data="{ isDraft: false }">
        @csrf
        @method('PUT')

        {{-- Top Admin Controls Panel --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm mb-6 space-y-4">
            <h3 class="text-sm font-semibold text-zendo-navy uppercase tracking-wider border-b border-gray-100 pb-2">
                Admin Status & Visibility Controls
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Form Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold">
                        <option value="draft" {{ old('status', $entry->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ old('status', $entry->status) === 'submitted' ? 'selected' : '' }}>Submitted / Under Review</option>
                        <option value="verified" {{ old('status', $entry->status) === 'verified' ? 'selected' : '' }}>Verified (Supply Head)</option>
                        <option value="recheck" {{ old('status', $entry->status) === 'recheck' ? 'selected' : '' }}>Needs Recheck</option>
                        <option value="rejected" {{ old('status', $entry->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Admin Approval Status</label>
                    <select name="admin_status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold">
                        <option value="pending" {{ old('admin_status', $entry->admin_status) === 'pending' || !in_array($entry->admin_status, ['approved', 'rejected']) ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('admin_status', $entry->admin_status) === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('admin_status', $entry->admin_status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Website Visibility</label>
                    <select name="show_on_website" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold">
                        <option value="1" {{ old('show_on_website', $entry->show_on_website) ? 'selected' : '' }}>Shown (Published on Website)</option>
                        <option value="0" {{ !old('show_on_website', $entry->show_on_website) ? 'selected' : '' }}>Hidden (Not Shown on Website)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Admin Note / Internal Remarks</label>
                <textarea name="admin_note" rows="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold" placeholder="Internal admin notes...">{{ old('admin_note', $entry->admin_note) }}</textarea>
            </div>
        </div>

        {{-- Master Form Partial --}}
        @include('field.properties._form')

        {{-- Sticky Admin Save Bar --}}
        <div class="sticky bottom-4 mt-6 bg-zendo-navy text-white p-4 rounded-xl shadow-lg flex items-center justify-between">
            <span class="text-sm font-medium">Editing Code: <span class="font-mono text-zendo-gold">{{ $entry->code }}</span></span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.property-entry-report.show', $entry) }}" class="px-4 py-2 text-xs font-medium text-gray-300 hover:text-white">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-zendo-gold text-zendo-navy font-semibold text-xs rounded-lg hover:bg-amber-400 transition-colors shadow">
                    Save Changes
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

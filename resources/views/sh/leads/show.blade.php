@extends('layouts.crm')

@section('title', 'Feasibility — ' . $lead->name)
@section('page-title', 'Feasibility Request')

@section('sidebar-links')
    <nav class="text-sm">
        <a href="{{ route('sh.leads.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white/80 hover:text-white">
            ← Back to Queue
        </a>
    </nav>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-2 space-y-5">

        {{-- Lead summary --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h2 class="text-xl font-heading text-zendo-navy mb-4">{{ $lead->name }}</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div><span class="font-medium">Phone:</span> {{ $lead->phone }}</div>
                @if($lead->email)
                <div><span class="font-medium">Email:</span> {{ $lead->email }}</div>
                @endif
                <div><span class="font-medium">Division:</span> {{ ucfirst($lead->division) }}</div>
                <div><span class="font-medium">Stage:</span>
                    <span class="stage-pill {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
                </div>
                <div><span class="font-medium">CC:</span> {{ $lead->assignedCC?->name ?? '—' }}</div>
                <div><span class="font-medium">Requested:</span> {{ $lead->feasibility_requested_at?->diffForHumans() ?? '—' }}</div>
                <div>
                    <span class="font-medium">SLA:</span>
                    @if($lead->sla_feasibility_breached)
                        <span class="text-red-600 font-semibold">⚠ Breached</span>
                    @elseif($lead->sla_feasibility_due_at)
                        {{ $lead->sla_feasibility_due_at->diffForHumans() }}
                    @else — @endif
                </div>
            </div>
            @if($lead->qualification_notes)
                <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-900">
                    <div class="text-xs font-semibold text-blue-500 mb-1">Lead Qualification Notes (from SE)</div>
                    {{ $lead->qualification_notes }}
                </div>
            @endif
        </div>

        {{-- Property snapshot --}}
        @if(!empty($propertySnapshot))
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-heading text-zendo-navy mb-3">Property Details</h3>
            <div class="grid grid-cols-2 gap-3 text-sm text-gray-600">
                <div><span class="font-medium">Title:</span> {{ $propertySnapshot['title'] }}</div>
                <div><span class="font-medium">Type:</span> {{ $propertySnapshot['property_type'] ?? '—' }}</div>
                <div><span class="font-medium">City:</span> {{ $propertySnapshot['city'] ?? '—' }}</div>
                <div><span class="font-medium">Location:</span> {{ $propertySnapshot['location'] ?? '—' }}</div>
                <div><span class="font-medium">Price:</span> ₹{{ number_format($propertySnapshot['price']) }}</div>
                <div><span class="font-medium">Area:</span> {{ $propertySnapshot['carpet_area'] ?? '—' }} sqft</div>
            </div>
        </div>
        @endif

        {{-- Respond form --}}
        @if($lead->feasibility_status === 'pending')
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-heading text-zendo-navy mb-4">Submit Feasibility Response</h3>
            <form data-ajax-form action="{{ route('sh.leads.respond', $lead) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Feasibility Status</label>
                    <div class="flex gap-3 flex-wrap">
                        @foreach(['feasible'=>['Feasible','green'],'not_feasible'=>['Not Feasible','red'],'conditional'=>['Conditional','orange']] as $val=>[$lbl,$col])
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="feasibility_status" value="{{ $val }}" required
                                class="accent-{{ $col }}-600">
                            <span class="stage-pill bg-{{ $col }}-100 text-{{ $col }}-700">{{ $lbl }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-red-500">*</span></label>
                    <textarea name="feasibility_notes" required rows="5" minlength="10" placeholder="Provide detailed feasibility assessment, conditions, availability, pricing notes..." class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm outline-none resize-none focus:ring-2 focus:ring-zendo-gold/50"></textarea>
                </div>
                <button type="submit" class="bg-zendo-navy text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:opacity-90">Submit Response</button>
            </form>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-heading text-zendo-navy mb-3">Your Response</h3>
            <div class="flex items-center gap-3 mb-3">
                @php $col = match($lead->feasibility_status) { 'feasible'=>'green','not_feasible'=>'red',default=>'orange' }; @endphp
                <span class="stage-pill bg-{{ $col }}-100 text-{{ $col }}-700 text-sm">
                    {{ ucfirst(str_replace('_',' ',$lead->feasibility_status)) }}
                </span>
                <span class="text-xs text-gray-500">{{ $lead->feasibility_responded_at?->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $lead->feasibility_notes }}</p>
        </div>
        @endif
    </div>

    {{-- Right: history --}}
    <div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-heading text-zendo-navy mb-4">Stage History</h3>
            <ol class="relative border-l border-gray-200 space-y-4 ml-3">
                @forelse($history as $h)
                <li class="ml-4">
                    <span class="absolute -left-1.5 mt-1 w-3 h-3 bg-zendo-gold rounded-full border-2 border-white"></span>
                    <div class="text-xs font-semibold text-gray-700">{{ $h->from_stage_label }} → {{ $h->to_stage_label }}</div>
                    @if($h->note)<p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($h->note, 100) }}</p>@endif
                    <time class="text-xs text-gray-400">{{ $h->created_at->diffForHumans() }}</time>
                </li>
                @empty
                    <li class="ml-4 text-xs text-gray-400">No history.</li>
                @endforelse
            </ol>
        </div>
    </div>
</div>
@endsection

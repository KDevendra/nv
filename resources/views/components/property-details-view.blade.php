{{--
    Entry point used by both owner/properties/show.blade.php and
    field/properties/show.blade.php — <x-property-details-view :property="$property" />.

    Dispatches to one of the 13 dedicated per-type files under
    components/property-details/*.blade.php, each of which mirrors its own
    form's actual section structure (see that directory's own doc comment).
    A legacy row with a NULL property_type resolves to warehouse, matching
    PropertyEntry::resolved_property_type used everywhere else this same
    rule applies (admin detail view, code-prefix generation).
--}}
@props(['property' => null])

@if($property)
    @php
        $slug = str_replace('_', '-', $property->resolved_property_type);
        $view = 'components.property-details.' . $slug;
    @endphp
    @if(\Illuminate\Support\Facades\View::exists($view))
        @include($view, ['property' => $property])
    @else
        {{-- Row carries a property_type this page doesn't recognise (e.g.
             data from before a 14th type existed) — fall back to the
             warehouse layout rather than showing a blank page. --}}
        @include('components.property-details.warehouse', ['property' => $property])
    @endif
@endif

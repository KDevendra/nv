{{--
    Commercial / Institutional Land — Property Details View.

    Mirrors resources/views/owner/properties/commercial-institutional-land/create.blade.php section-by-section: same
    sections, same order, same field labels as that form (see
    config/property_entry_sections.php['commercial_institutional_land'], generated directly from
    that form's own markup). Rendering logic (formatting, conditional
    show/hide, photo/document gallery) lives once in the shared
    <x-property-details-fields> engine — this file only says WHICH fields
    and in WHAT order, exactly as the form itself defines them.
--}}
@props(['property'])

<x-property-details-fields :property="$property" :sections="config('property_entry_sections.commercial_institutional_land')" />

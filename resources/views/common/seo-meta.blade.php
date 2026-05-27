@if(isset($seoMeta) && $seoMeta)
    @if($seoMeta->description)
        <meta name="description" content="{{ $seoMeta->description }}">
    @endif
    @if($seoMeta->keywords)
        <meta name="keywords" content="{{ $seoMeta->keywords }}">
    @endif
    @if($seoMeta->og_title)
        <meta property="og:title" content="{{ $seoMeta->og_title }}">
    @endif
    @if($seoMeta->og_description)
        <meta property="og:description" content="{{ $seoMeta->og_description }}">
    @endif
    @if($seoMeta->og_image)
        <meta property="og:image" content="{{ $seoMeta->og_image }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($seoMeta->schema_markup)
        <script type="application/ld+json">{!! $seoMeta->schema_markup !!}</script>
    @endif
    @if($seoMeta->faq_schema)
        <script type="application/ld+json">{!! $seoMeta->faq_schema !!}</script>
    @endif
@endif

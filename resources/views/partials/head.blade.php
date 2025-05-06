<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
<!-- Use model-viewer v3.3.0 -->
{{-- <script type="module" src="https://unpkg.com/@google/model-viewer@3.3.0/dist/model-viewer.min.js"></script> --}}

@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('scripts')
@fluxAppearance

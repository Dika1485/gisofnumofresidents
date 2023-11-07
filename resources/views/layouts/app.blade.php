<!DOCTYPE html>
<html>
<head>
    <title>GIS of Village in Central Java</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     <script src="https://unpkg.com/shpjs@latest/dist/shp.js"></script>
     <script src="{{ asset('js/leaflet.shpfile.js') }}"></script>
     <script src="{{ asset('js/shp.js') }}"></script>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    @stack('scripts') <!-- Stack for additional JavaScript scripts -->
</body>
</html>

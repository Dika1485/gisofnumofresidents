<!DOCTYPE html>
<html>
<head>
    <title>GIS of Village in Java</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        #map-container {
            position: relative;
            height: 100vh;
        }

        #provinceSelectorCard {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        #attributeSelectorCard {
            position: absolute;
            top: 60px;
            right: 10px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #map {
            height: 100%;
        }
        #legend {
            position: absolute;
            top: 110px;
            right: 10px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .legend-color {
            display: inline-block;
            width: 20px;
            height: 10px;
            margin-right: 5px;
        }
    </style>
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

    @stack('scripts')
</body>
</html>

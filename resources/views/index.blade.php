@extends('layouts.app')

@section('content')
    <div id="map-container">
        <div id="provinceSelectorCard">
            <label for="provinceSelector">Choose Province:</label>
            <select id="provinceSelector" class="form-select" onchange="changeProvince()">
                <option value="centralJava">Central Java</option>
                <option value="westJava">West Java</option>
                <option value="eastJava">East Java</option>
                <option value="diy">Daerah Istimewa Yogyakarta (DIY)</option>
                <option value="dkiJakarta">Daerah Khusus Ibukota (DKI) Jakarta</option>
                <option value="banten">Banten</option>
            </select>
        </div>
        <div id="map"></div>
    </div>
@endsection

@push('scripts')
<script>
    let shapefileLayer = L.layerGroup();
    let shpfile;
    const map = L.map('map');
    let bounds=L.latLngBounds([-11, 95], [-6, 113]);;

    map.setMaxBounds(bounds);
    map.on('drag', function () {
        map.panInsideBounds(bounds, { animate: false });
    });

    const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        minZoom: 5,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function changeProvince() {
        const selectedProvince = document.getElementById('provinceSelector').value;

        if (shapefileLayer) {
            shapefileLayer.clearLayers();
        }

        const shapefilePath = getShapefilePath(selectedProvince);
        shpfile = new L.Shapefile(shapefilePath, {
            onEachFeature: function (feature, layer) {
                if (feature.properties) {
                    layer.bindPopup(
                        Object.keys(feature.properties)
                            .map(function (k) {
                                return k + ': ' + feature.properties[k];
                            })
                            .join('<br />'),
                        {
                            maxHeight: 200
                        }
                    );
                }
            },
            style: function (feature) {
                const randomColor = getRandomColor();

                const style = {
                    fillColor: randomColor,
                    weight: 1,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.7
                };

                return style;
            }
        });

        shpfile.addTo(map);
        shapefileLayer = shpfile;
        bounds = getBoundsByProvince(selectedProvince);
        const minZoom = getMinZoomByProvince(selectedProvince);
        tiles.removeFrom(map);

        // const shapefileBounds = shpfile.getBounds();
        // const center = shapefileBounds.getCenter();
        // const zoom = calculateZoom(shapefileBounds, map.getSize());
        // const minZoom = 8;

        shpfile.once('data:loaded', function () {
            console.log('Shapefile loaded successfully');
            shpfile.eachLayer(function (layer) {
                shapefileLayer.addLayer(layer);
                layer.bindPopup(createPopupContent(layer.feature.properties), {
                    maxHeight: 200
                });
                layer.on('click', function (e) {
                    e.target.openPopup();
                });
            });
            shapefileLayer.addTo(map);
            updateMapProperties(bounds, minZoom);
        });
    }
    function getBoundsByProvince(province) {
        switch (province) {
            case 'centralJava':
                return L.latLngBounds([-9, 108], [-5, 112]);
            case 'westJava':
                return L.latLngBounds([-8, 106], [-5, 109]);
            case 'eastJava':
                return L.latLngBounds([-9, 110], [-7, 115]);
            case 'diy':
                return L.latLngBounds([-8.5, 110], [-7.5, 111]);
            case 'dkiJakarta':
                return L.latLngBounds([-6.5, 106.5], [-5, 107]);
            case 'banten':
                return L.latLngBounds([-8, 105], [-5, 107]);
            default:
                return L.latLngBounds([-11, 95], [-6, 113]);
        }
    }

    function getMinZoomByProvince(province) {
        switch (province) {
            case 'centralJava':
                return 8;
            case 'westJava':
                return 8;
            case 'eastJava':
                return 7;
            case 'diy':
                return 10;
            case 'dkiJakarta':
                return 9;
            case 'banten':
                return 9;
            default:
                return 8; // Default nilai minZoom
        }
    }

    function getShapefilePath(province) {
        switch (province) {
            case 'centralJava':
                return "{{ asset('shapefile/BATAS DESA DESEMBER 2019 DUKCAPIL JAWA TENGAH.zip') }}";
            case 'westJava':
                return "{{ asset('shapefile/BATAS DESA DESEMBER 2019 DUKCAPIL JAWA BARAT.zip') }}";
            case 'eastJava':
                return "{{ asset('shapefile/BATAS DESA DESEMBER 2019 DUKCAPIL JAWA TIMUR.zip') }}";
            case 'diy':
                return "{{ asset('shapefile/BATAS DESA DESEMBER 2019 DUKCAPIL DI YOGYAKARTA.zip') }}";
            case 'dkiJakarta':
                return "{{ asset('shapefile/BATAS DESA DESEMBER 2019 DUKCAPIL DKI JAKARTA.zip') }}";
            case 'banten':
                return "{{ asset('shapefile/BATAS DESA DESEMBER 2019 DUKCAPIL BANTEN.zip') }}";
            default:
                return "";
        }
    }

    function calculateZoom(bounds, mapSize) {
        const worldDim = 256;
        const zoomMax = 19;
        let zoom = 0;
        while (worldDim < Math.max(mapSize.x, mapSize.y) && zoom < zoomMax) {
            zoom++;
            worldDim *= 2;
        }
        return zoom;
    }

    window.onload = function () {
        // map.setView([-7.150975, 110.140259], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            minZoom: 6,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        changeProvince();
    };

    function updateMapProperties(bounds, minZoom) {
        map.options.minZoom = minZoom;
        map.setMaxBounds(bounds);
        map.fitBounds(bounds);
    }

    function createPopupContent(properties) {
        let popupContent = '<table>';
        for (let key in properties) {
            popupContent += `<tr><td>${key}:</td><td>${properties[key]}</td></tr>`;
        }
        popupContent += '</table>';
        return popupContent;
    }
</script>
<script>
    $(document).ready(function() {
        $('#provinceSelector').select2();
    });
</script>
@endpush

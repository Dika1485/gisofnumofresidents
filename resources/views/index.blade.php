@extends('layouts.app')

@section('content')
<div id="map" style="height: 100vh;"></div>
@endsection

@push('scripts')
<script>
    const map = L.map('map', {
        center: [-7.150975, 110.140259],
        zoom: 8,
    });

    const bounds = L.latLngBounds([-9, 108], [-5, 112]); // batas geografis untuk Jawa Tengah

    map.setMaxBounds(bounds);
    map.on('drag', function () {
        map.panInsideBounds(bounds, { animate: false });
    });

    const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        minZoom: 8,
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

    const shpfile = new L.Shapefile("{{ asset('shapefile/BATAS_DESA_DESEMBER_2019_DUKCAPIL_JAWA_TENGAH.zip') }}", {
    // const shpfile = new L.Shapefile("{{ asset('shapefile/BATAS KECAMATAN DESEMBER 2019 DUKCAPIL.zip') }}", {
    // from https://www.indonesia-geospasial.com/2020/04/download-shapefile-shp-batas.html
    // const shpfile = new L.Shapefile("{{ asset('shapefile/IDN_adm.zip') }}", {
    // from https://www.diva-gis.org/gdata
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
    });

    function createPopupContent(properties) {
        let popupContent = '<table>';
        for (let key in properties) {
            popupContent += `<tr><td>${key}:</td><td>${properties[key]}</td></tr>`;
        }
        popupContent += '</table>';
        return popupContent;
    }

    // function onLocationFound(e) {
	// 	const radius = e.accuracy / 2;

	// 	const locationMarker = L.marker(e.latlng).addTo(map)
	// 		.bindPopup(`You are within ${radius} meters from this point`).openPopup();

	// 	const locationCircle = L.circle(e.latlng, radius).addTo(map);
    //     shpfile.eachLayer(function (layer) {
    //         layer.on('click', function (event) {
    //             // Tampilkan popup dengan detail dari Shapefile ketika lapisan diklik
    //             layer.openPopup();
    //         });
    //     });
	// }

	// function onLocationError(e) {
	// 	alert(e.message);
	// }

	// map.on('locationfound', onLocationFound);
	// map.on('locationerror', onLocationError);

	// map.locate({setView: true, maxZoom: 16});
</script>
@endpush

<?php
require 'views/layouts/header.php';
?>

<!-- OpenLayers CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v8.2.0/ol.css">
<!-- OpenLayers JS -->
<script src="https://cdn.jsdelivr.net/npm/ol@v8.2.0/dist/ol.js"></script>

<style>
    .map-container {
        width: 100%;
        height: calc(100vh - 80px); /* Adjust based on navbar height */
        position: relative;
    }
    .ol-popup {
        position: absolute;
        background-color: white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        bottom: 12px;
        left: -50px;
        min-width: 280px;
        max-width: 320px;
        display: none;
    }
    .ol-popup:after, .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
    }
    .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;
    }
    .ol-popup:before {
        border-top-color: #e5e7eb;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;
    }
    .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 8px;
        right: 8px;
        color: #9ca3af;
        font-size: 16px;
    }
    .ol-popup-closer:hover {
        color: #4b5563;
    }
    .popup-img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .popup-badge {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        padding: 2px 6px;
        border-radius: 4px;
        color: white;
        display: inline-block;
        margin-bottom: 4px;
    }
    .bg-tour { background-color: #ef4444; }
    .bg-accommodation { background-color: #3b82f6; }
    .bg-culinary { background-color: #f97316; }
    .bg-transportation { background-color: #10b981; }
</style>

<div class="relative">
    <!-- Map Element -->
    <div id="map" class="map-container"></div>

    <!-- Map Legend -->
    <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm p-4 rounded-xl shadow-lg border border-gray-100 z-10 hidden sm:block">
        <h4 class="font-bold text-gray-800 text-sm mb-3">Legenda Peta</h4>
        <div class="flex items-center mb-2">
            <span class="w-4 h-4 rounded-full bg-red-500 mr-2 border-2 border-white shadow-sm"></span>
            <span class="text-sm">Destinasi Wisata</span>
        </div>
        <div class="flex items-center mb-2">
            <span class="w-4 h-4 rounded-full bg-blue-500 mr-2 border-2 border-white shadow-sm"></span>
            <span class="text-sm">Penginapan</span>
        </div>
        <div class="flex items-center mb-2">
            <span class="w-4 h-4 rounded-full bg-orange-500 mr-2 border-2 border-white shadow-sm"></span>
            <span class="text-sm">Kuliner</span>
        </div>
        <div class="flex items-center">
            <span class="w-4 h-4 rounded-full bg-emerald-500 mr-2 border-2 border-white shadow-sm"></span>
            <span class="text-sm">Transportasi</span>
        </div>
    </div>

    <!-- Popup Element -->
    <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer">&times;</a>
        <div id="popup-content"></div>
    </div>
</div>

<script>
    // 1. Data Sources from PHP
    const featuresData = <?= $mapFeaturesJson ?>;

    // 2. Setup Popup Overlay
    const container = document.getElementById('popup');
    const content = document.getElementById('popup-content');
    const closer = document.getElementById('popup-closer');

    const overlay = new ol.Overlay({
        element: container,
        autoPan: {
            animation: {
                duration: 250,
            },
        },
    });

    closer.onclick = function () {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
    };

    // 3. Format Features & Layers
    const format = new ol.format.GeoJSON({
        featureProjection: 'EPSG:3857' // OpenStreetMap projection
    });

    const vectorSource = new ol.source.Vector({
        features: format.readFeatures(featuresData)
    });

    // Style Function
    function styleFunction(feature) {
        const category = feature.get('category');
        let color = '#ef4444';
        if (category === 'accommodation') {
            color = '#3b82f6';
        } else if (category === 'culinary') {
            color = '#f97316';
        } else if (category === 'transportation') {
            color = '#10b981';
        }

        return new ol.style.Style({
            image: new ol.style.Circle({
                radius: 8,
                fill: new ol.style.Fill({ color: color }),
                stroke: new ol.style.Stroke({
                    color: '#ffffff',
                    width: 2
                })
            })
        });
    }

    const vectorLayer = new ol.layer.Vector({
        source: vectorSource,
        style: styleFunction
    });

    // 4. Map Initialization
    const diengCenter = ol.proj.fromLonLat([109.900, -7.210]); // Center of Dieng

    const map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            }),
            vectorLayer
        ],
        overlays: [overlay],
        view: new ol.View({
            center: diengCenter,
            zoom: 13,
            maxZoom: 18,
            minZoom: 10
        })
    });

    // Load Kecamatan Boundaries from Peta directory if available
    fetch('peta/33.07_kecamatan.geojson')
        .then(response => {
            if (response.ok) return response.json();
            throw new Error('Boundary file not found');
        })
        .then(geojsonData => {
            const boundarySource = new ol.source.Vector({
                features: format.readFeatures(geojsonData)
            });
            const boundaryLayer = new ol.layer.Vector({
                source: boundarySource,
                style: new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'rgba(13, 148, 136, 0.6)',
                        width: 1.5,
                        lineDash: [4, 4]
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(13, 148, 136, 0.05)'
                    })
                })
            });
            map.getLayers().insertAt(1, boundaryLayer);
        })
        .catch(err => console.log('Kecamatan boundary layer skipped:', err.message));

    // 5. Interactivity: Click on Points
    map.on('singleclick', function (evt) {
        const feature = map.forEachFeatureAtPixel(evt.pixel, function (feat) {
            return feat;
        });

        if (feature) {
            const coordinates = feature.getGeometry().getCoordinates();
            const props = feature.getProperties();

            // Ignore boundary features
            if (!props.name || props.KECAMATAN) return;

            const name = props.name;
            const description = props.description || '';
            const image = props.image;
            const type = props.type;
            const category = props.category;
            const url = props.url;

            let badgeClass = 'bg-tour';
            if (category === 'accommodation') badgeClass = 'bg-accommodation';
            if (category === 'culinary') badgeClass = 'bg-culinary';
            if (category === 'transportation') badgeClass = 'bg-transportation';

            content.innerHTML = `
                <img src="${image}" class="popup-img" alt="${name}">
                <div>
                    <span class="popup-badge ${badgeClass}">${type}</span>
                    <h3 class="font-bold text-lg text-gray-800 mb-1">${name}</h3>
                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">${description}</p>
                    <a href="${url}" class="block w-full bg-teal-600 hover:bg-teal-700 text-white text-center py-2 rounded-lg text-sm font-semibold transition">
                        Lihat Detail
                    </a>
                </div>
            `;

            overlay.setPosition(coordinates);
            container.style.display = 'block';
        } else {
            overlay.setPosition(undefined);
            closer.blur();
        }
    });

    // Pointer cursor on hover over features
    map.on('pointermove', function (e) {
        const pixel = map.getEventPixel(e.originalEvent);
        const hit = map.hasFeatureAtPixel(pixel);
        map.getTargetElement().style.cursor = hit ? 'pointer' : '';
    });
</script>

<?php require 'views/layouts/footer.php'; ?>

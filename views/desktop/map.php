<?php include 'views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v8.2.0/ol.css" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/ol@v8.2.0/dist/ol.js"></script>

<style>
    .map {
        width: 100%;
        height: 80vh; /* Full height minus header */
    }
    .ol-popup {
        position: absolute;
        background-color: white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        padding: 0;
        border-radius: 12px;
        border: none;
        bottom: 15px;
        left: -50%;
        transform: translateX(-50%);
        min-width: 300px;
        max-width: 320px;
        overflow: hidden;
        z-index: 100;
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
        left: 50%;
        margin-left: -10px;
    }
    .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        background: rgba(0,0,0,0.5);
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        font-size: 18px;
        z-index: 2;
        transition: background 0.3s;
    }
    .ol-popup-closer:hover {
        background: rgba(0,0,0,0.8);
    }
    .popup-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .popup-content {
        padding: 15px;
    }
    .popup-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        color: white;
        margin-bottom: 8px;
    }
    .bg-tour { background-color: #ef4444; }
    .bg-accommodation { background-color: #3b82f6; }
    .bg-culinary { background-color: #f97316; }
</style>

<div class="relative">
    <div id="map" class="map"></div>
    
    <!-- Legend -->
    <div class="absolute bottom-5 left-5 bg-white p-4 rounded-lg shadow-lg z-10 hidden md:block">
        <h3 class="font-bold text-gray-800 mb-2">Legenda</h3>
        <div class="flex items-center mb-2">
            <span class="w-4 h-4 rounded-full bg-red-500 mr-2 border-2 border-white shadow-sm"></span>
            <span class="text-sm">Destinasi Wisata</span>
        </div>
        <div class="flex items-center mb-2">
            <span class="w-4 h-4 rounded-full bg-blue-500 mr-2 border-2 border-white shadow-sm"></span>
            <span class="text-sm">Penginapan</span>
        </div>
        <div class="flex items-center">
            <span class="w-4 h-4 rounded-full bg-orange-500 mr-2 border-2 border-white shadow-sm"></span>
            <span class="text-sm">Kuliner</span>
        </div>
    </div>
</div>

<div id="popup" class="ol-popup">
    <a href="#" id="popup-closer" class="ol-popup-closer">&times;</a>
    <div id="popup-content"></div>
</div>

<script>
    // Data from PHP
    const mapData = <?= json_encode($mapData) ?>;

    // Elements
    const container = document.getElementById('popup');
    const content = document.getElementById('popup-content');
    const closer = document.getElementById('popup-closer');

    // Overlay for Popup
    const overlay = new ol.Overlay({
        element: container,
        autoPan: {
            animation: { duration: 250 }
        }
    });

    closer.onclick = function() {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
    };

    // Vector Source for Markers
    const vectorSource = new ol.source.Vector();

    // Create Features from Data
    mapData.forEach(item => {
        const feature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.fromLonLat([item.lon, item.lat])),
            name: item.name,
            type: item.type,
            category: item.category,
            description: item.description,
            image: item.image,
            url: item.url
        });
        vectorSource.addFeature(feature);
    });

    // Style Function
    function styleFunction(feature) {
        const category = feature.get('category');
        let color = '#ef4444'; // red for tour
        if (category === 'accommodation') color = '#3b82f6'; // blue
        if (category === 'culinary') color = '#f97316'; // orange

        return new ol.style.Style({
            image: new ol.style.Circle({
                radius: 10,
                fill: new ol.style.Fill({color: color}),
                stroke: new ol.style.Stroke({color: 'white', width: 3})
            })
        });
    }

    // Vector Layer for Markers
    const markerLayer = new ol.layer.Vector({
        source: vectorSource,
        style: styleFunction
    });

    // Peta Desa GeoJSON Layer
    const villageLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            url: 'peta_desa.geojson',
            format: new ol.format.GeoJSON()
        }),
        style: new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: 'rgba(13, 148, 136, 0.8)', // Teal stroke
                width: 1
            }),
            fill: new ol.style.Fill({
                color: 'rgba(13, 148, 136, 0.1)' // Light teal fill
            })
        })
    });

    // Map Initialization
    const map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            }),
            villageLayer,
            markerLayer
        ],
        overlays: [overlay],
        view: new ol.View({
            center: ol.proj.fromLonLat([109.68, -7.39]), // Banjarnegara Center
            zoom: 12
        })
    });

    // Fit view to all markers
    if (vectorSource.getFeatures().length > 0) {
        const extent = vectorSource.getExtent();
        map.getView().fit(extent, {
            padding: [50, 50, 50, 50],
            maxZoom: 15,
            duration: 1000 // Animation duration
        });
    }

    // Click Interaction
    map.on('singleclick', function(evt) {
        const feature = map.forEachFeatureAtPixel(evt.pixel, function(feature) {
            return feature;
        });

        if (feature) {
            // Check if it's a marker (has 'name' property) and not just a village polygon
            const name = feature.get('name');
            if (name) {
                const geometry = feature.getGeometry();
                const coordinate = geometry.getCoordinates();
                
                const image = feature.get('image');
                const description = feature.get('description');
                const type = feature.get('type');
                const category = feature.get('category');
                const url = feature.get('url');
                
                let badgeClass = 'bg-tour';
                if(category === 'accommodation') badgeClass = 'bg-accommodation';
                if(category === 'culinary') badgeClass = 'bg-culinary';

                content.innerHTML = `
                    <img src="${image}" class="popup-img" alt="${name}">
                    <div class="popup-content">
                        <span class="popup-badge ${badgeClass}">${type}</span>
                        <h3 class="font-bold text-lg text-gray-800 mb-1">${name}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">${description}</p>
                        <a href="${url}" class="block w-full bg-teal-600 hover:bg-teal-700 text-white text-center py-2 rounded-md text-sm font-bold transition">
                            Lihat Detail
                        </a>
                    </div>
                `;
                
                overlay.setPosition(coordinate);
            }
        } else {
            overlay.setPosition(undefined);
        }
    });

    // Change cursor on hover
    map.on('pointermove', function(e) {
        const pixel = map.getEventPixel(e.originalEvent);
        const hit = map.hasFeatureAtPixel(pixel);
        map.getTargetElement().style.cursor = hit ? 'pointer' : '';
    });
</script>

<?php include 'views/layouts/footer.php'; ?>

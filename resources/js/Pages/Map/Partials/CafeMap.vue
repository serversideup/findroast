<template>
    <div ref="mapContainer" class="w-full h-full" />
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Loader } from '@googlemaps/js-api-loader';

const props = defineProps({
    cafes: { type: Array, required: true },
    selectedCafe: { type: Object, default: null },
    hoveredCafe: { type: Object, default: null },
});

const emit = defineEmits(['select-cafe']);

const mapContainer = ref(null);
const apiKey = computed(() => usePage().props.google_maps_api_key);

let googleMap = null;
let markers = new Map(); // cafe.id -> { marker, element }

// Create custom marker element
const createMarkerElement = (cafe, isActive = false, isHovered = false) => {
    const el = document.createElement('div');
    el.style.cursor = 'pointer';
    el.style.transition = 'transform 150ms ease';

    const dot = document.createElement('div');
    dot.style.width = isActive ? '20px' : isHovered ? '16px' : '12px';
    dot.style.height = isActive ? '20px' : isHovered ? '16px' : '12px';
    dot.style.borderRadius = '50%';
    dot.style.backgroundColor = isActive ? '#b45309' : '#d97706'; // amber-700 : amber-500
    dot.style.border = `2px solid ${isActive ? '#92400e' : '#ffffff'}`; // amber-800 : white
    dot.style.boxShadow = '0 2px 6px rgba(0,0,0,0.3)';
    dot.style.transition = 'all 150ms ease';

    el.appendChild(dot);

    if (isActive) {
        el.style.transform = 'scale(1.15)';
        el.style.zIndex = '10';
    }

    return el;
};

onMounted(async () => {
    const loader = new Loader({
        apiKey: apiKey.value,
        version: 'beta',
    });

    const { Map } = await loader.importLibrary('maps');
    await google.maps.importLibrary('marker');

    googleMap = new Map(mapContainer.value, {
        center: { lat: 39.50, lng: -98.35 },
        zoom: 4,
        mapId: 'findroast-cafe-map',
        zoomControl: true,
        fullscreenControl: false,
        mapTypeControl: false,
        streetViewControl: false,
        clickableIcons: false,
        gestureHandling: 'greedy',
    });

    // Initial markers
    syncMarkers(props.cafes);
    fitBounds(props.cafes);
});

const syncMarkers = (cafes) => {
    if (!googleMap) return;

    const cafeIds = new Set(cafes.map(c => c.id));

    // Remove markers for cafes no longer in the list
    for (const [id, entry] of markers) {
        if (!cafeIds.has(id)) {
            entry.marker.map = null;
            markers.delete(id);
        }
    }

    // Add/update markers
    cafes.forEach(cafe => {
        if (!cafe.latitude || !cafe.longitude) return;

        if (markers.has(cafe.id)) {
            // Already exists — update styling if needed
            updateMarkerStyle(cafe.id);
        } else {
            // Create new marker
            const element = createMarkerElement(cafe);

            const marker = new google.maps.marker.AdvancedMarkerElement({
                map: googleMap,
                position: {
                    lat: parseFloat(cafe.latitude),
                    lng: parseFloat(cafe.longitude),
                },
                content: element,
                title: cafe.name,
            });

            marker.addListener('click', () => {
                emit('select-cafe', cafe);
            });

            markers.set(cafe.id, { marker, element, cafe });
        }
    });
};

const updateMarkerStyle = (cafeId) => {
    const entry = markers.get(cafeId);
    if (!entry) return;

    const isActive = props.selectedCafe?.id === cafeId;
    const isHovered = props.hoveredCafe?.id === cafeId;

    const newElement = createMarkerElement(entry.cafe, isActive, isHovered);
    entry.marker.content = newElement;
    entry.element = newElement;
};

const fitBounds = (cafes) => {
    if (!googleMap || cafes.length === 0) return;

    const bounds = new google.maps.LatLngBounds();
    cafes.forEach(cafe => {
        if (cafe.latitude && cafe.longitude) {
            bounds.extend({
                lat: parseFloat(cafe.latitude),
                lng: parseFloat(cafe.longitude),
            });
        }
    });

    if (cafes.length === 1) {
        googleMap.setCenter(bounds.getCenter());
        googleMap.setZoom(14);
    } else {
        googleMap.fitBounds(bounds, { top: 50, right: 50, bottom: 50, left: 50 });
    }
};

// Watch filtered cafes
watch(() => props.cafes, (newCafes) => {
    syncMarkers(newCafes);
    fitBounds(newCafes);
}, { deep: true });

// Watch selected cafe
watch(() => props.selectedCafe, (newCafe, oldCafe) => {
    if (oldCafe) updateMarkerStyle(oldCafe.id);
    if (newCafe) {
        updateMarkerStyle(newCafe.id);
        if (googleMap && newCafe.latitude && newCafe.longitude) {
            googleMap.panTo({
                lat: parseFloat(newCafe.latitude),
                lng: parseFloat(newCafe.longitude),
            });
            if (googleMap.getZoom() < 12) {
                googleMap.setZoom(14);
            }
        }
    }
});

// Watch hovered cafe
watch(() => props.hoveredCafe, (newCafe, oldCafe) => {
    if (oldCafe) updateMarkerStyle(oldCafe.id);
    if (newCafe) updateMarkerStyle(newCafe.id);
});
</script>

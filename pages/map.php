<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = "Peta Bantuan - Bantuin Yuk";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

// Get requests for map
$sql = "SELECT r.*, u.name as user_name, GROUP_CONCAT(t.name) as tag_names,
               r.latitude, r.longitude
        FROM requests r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN request_tags rt ON r.id = rt.request_id 
        LEFT JOIN tags t ON rt.tag_id = t.id 
        WHERE r.status = 'open' AND r.latitude IS NOT NULL AND r.longitude IS NOT NULL
        GROUP BY r.id";
$result = $conn->query($sql);
$requests = [];
while($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 animate-fade-in">
                        Peta Bantuan
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Temukan permintaan bantuan di sekitar lokasi Anda
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <button id="locate-me" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-location-arrow mr-2"></i>
                        Cari Lokasi Saya
                    </button>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Filters Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 animate-slide-up">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-filter mr-2 text-blue-500"></i>
                            Filter Peta
                        </h3>
                        
                        <!-- Help Type Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Bantuan</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="help_type" value="request" checked 
                                           class="filter-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Butuh Bantuan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="help_type" value="offer" checked
                                           class="filter-checkbox h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Menawarkan Bantuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Urgency Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Tingkat Urgensi</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="urgency" value="critical" checked
                                           class="filter-checkbox h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Kritis</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="urgency" value="high" checked
                                           class="filter-checkbox h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Tinggi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="urgency" value="medium" checked
                                           class="filter-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Sedang</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="urgency" value="low" checked
                                           class="filter-checkbox h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Rendah</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tag Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Kategori</label>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                <?php 
                                $tags = getAllTags();
                                foreach($tags as $tag): 
                                ?>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tags" value="<?php echo $tag['id']; ?>" checked
                                               class="filter-checkbox h-4 w-4 focus:ring-gray-500 border-gray-300 rounded"
                                               style="color: <?php echo $tag['color']; ?>">
                                        <span class="ml-2 text-sm text-gray-700"><?php echo htmlspecialchars($tag['name']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <button id="apply-map-filters" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Terapkan Filter
                        </button>
                    </div>

                    <!-- Legend -->
                    <div class="bg-white rounded-lg shadow p-6 mt-6 animate-slide-up" style="animation-delay: 0.1s">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-map-signs mr-2 text-purple-500"></i>
                            Legenda Peta
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Kritis</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-orange-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Tinggi</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Sedang</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Rendah</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-purple-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Menawarkan Bantuan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow overflow-hidden animate-slide-up" style="animation-delay: 0.2s">
                        <div class="h-96 lg:h-[600px] relative" id="map">
                            <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                                    <p class="text-gray-600">Memuat peta...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requests List -->
                    <div class="mt-6 bg-white rounded-lg shadow overflow-hidden animate-slide-up" style="animation-delay: 0.3s">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list mr-2 text-green-500"></i>
                                Daftar Permintaan di Peta
                                <span class="ml-2 bg-green-100 text-green-800 text-sm px-2 py-1 rounded-full" id="requests-count">
                                    <?php echo count($requests); ?>
                                </span>
                            </h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <?php if (!empty($requests)): ?>
                                <div class="divide-y divide-gray-200">
                                    <?php foreach($requests as $request): ?>
                                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150 map-request-item" 
                                             data-request-id="<?php echo $request['id']; ?>"
                                             data-lat="<?php echo $request['latitude']; ?>"
                                             data-lng="<?php echo $request['longitude']; ?>"
                                             data-help-type="<?php echo $request['help_type']; ?>"
                                             data-urgency="<?php echo $request['urgency']; ?>"
                                             data-tags="<?php echo htmlspecialchars($request['tag_names']); ?>">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-medium text-gray-900 mb-1">
                                                        <?php echo htmlspecialchars($request['title']); ?>
                                                    </h4>
                                                    <p class="text-sm text-gray-500 mb-2 line-clamp-2">
                                                        <?php echo htmlspecialchars($request['description']); ?>
                                                    </p>
                                                    <div class="flex items-center text-xs text-gray-500 space-x-4">
                                                        <span class="flex items-center">
                                                            <i class="fas fa-user mr-1"></i>
                                                            <?php echo htmlspecialchars($request['user_name']); ?>
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                                            <?php echo htmlspecialchars($request['location']); ?>
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-<?php echo getUrgencyIcon($request['urgency']); ?> mr-1"></i>
                                                            <?php echo ucfirst($request['urgency']); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <button class="ml-4 text-blue-600 hover:text-blue-800 transition-colors duration-200 focus-view"
                                                        data-lat="<?php echo $request['latitude']; ?>"
                                                        data-lng="<?php echo $request['longitude']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <i class="fas fa-map-marker-alt text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">Tidak ada permintaan bantuan dengan lokasi yang terdeteksi.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
// Map initialization
let map;
let markers = [];
let userLocation = null;

document.addEventListener('DOMContentLoaded', function() {
    initializeMap();
    setupEventListeners();
});

function initializeMap() {
    // Initialize map centered on Indonesia
    map = L.map('map').setView([-2.5489, 118.0149], 5);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Add markers for requests
    addRequestMarkers();

    // Add scale control
    L.control.scale().addTo(map);
}

function addRequestMarkers() {
    // Clear existing markers
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    const requestItems = document.querySelectorAll('.map-request-item');
    
    requestItems.forEach(item => {
        if (item.style.display !== 'none') {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            const requestId = item.dataset.requestId;
            const helpType = item.dataset.helpType;
            const urgency = item.dataset.urgency;
            
            if (!isNaN(lat) && !isNaN(lng)) {
                const markerColor = getMarkerColor(urgency, helpType);
                
                const marker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div class="w-6 h-6 rounded-full border-2 border-white shadow-lg" style="background-color: ${markerColor}"></div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    })
                }).addTo(map);
                
                // Add popup
                const title = item.querySelector('h4').textContent;
                const description = item.querySelector('p').textContent;
                const userName = item.querySelector('.fa-user').parentNode.textContent.trim();
                const location = item.querySelector('.fa-map-marker-alt').parentNode.textContent.trim();
                
                marker.bindPopup(`
                    <div class="p-2 max-w-xs">
                        <h3 class="font-semibold text-gray-900 mb-2">${title}</h3>
                        <p class="text-sm text-gray-600 mb-3">${description}</p>
                        <div class="space-y-1 text-xs text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 w-4"></i>
                                <span>${userName}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                                <span>${location}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-${getUrgencyIcon(urgency)} mr-2 w-4"></i>
                                <span>${urgency.charAt(0).toUpperCase() + urgency.slice(1)}</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="../request/view.php?id=${requestId}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                `);
                
                markers.push(marker);
                
                // Add click event to list items
                item.addEventListener('click', function() {
                    map.setView([lat, lng], 13);
                    marker.openPopup();
                });
            }
        }
    });
}

function getMarkerColor(urgency, helpType) {
    if (helpType === 'offer') return '#8B5CF6'; // Purple for offers
    
    switch (urgency) {
        case 'critical': return '#EF4444'; // Red
        case 'high': return '#F59E0B'; // Orange
        case 'medium': return '#3B82F6'; // Blue
        case 'low': return '#10B981'; // Green
        default: return '#6B7280'; // Gray
    }
}

function getUrgencyIcon(urgency) {
    switch (urgency) {
        case 'critical': return 'skull-crossbones';
        case 'high': return 'exclamation-circle';
        case 'medium': return 'exclamation';
        case 'low': return 'clock';
        default: return 'question';
    }
}

function setupEventListeners() {
    // Locate me button
    document.getElementById('locate-me').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    userLocation = [position.coords.latitude, position.coords.longitude];
                    map.setView(userLocation, 13);
                    
                    // Add user location marker
                    if (window.userMarker) {
                        map.removeLayer(window.userMarker);
                    }
                    
                    window.userMarker = L.marker(userLocation, {
                        icon: L.divIcon({
                            className: 'user-location-marker',
                            html: '<div class="w-4 h-4 bg-blue-600 rounded-full border-2 border-white shadow-lg animate-pulse"></div>',
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        })
                    }).addTo(map).bindPopup('Lokasi Anda').openPopup();
                },
                function(error) {
                    console.error('Error getting location:', error);
                    alert('Tidak dapat mengakses lokasi Anda. Pastikan Anda mengizinkan akses lokasi.');
                }
            );
        } else {
            alert('Browser Anda tidak mendukung geolokasi.');
        }
    });

    // Focus view buttons
    document.querySelectorAll('.focus-view').forEach(button => {
        button.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            map.setView([lat, lng], 13);
        });
    });

    // Map filters
    document.getElementById('apply-map-filters').addEventListener('click', function() {
        applyMapFilters();
    });

    // Enter key in search
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyMapFilters();
        }
    });
}

function applyMapFilters() {
    const helpTypeFilters = Array.from(document.querySelectorAll('input[name="help_type"]:checked')).map(cb => cb.value);
    const urgencyFilters = Array.from(document.querySelectorAll('input[name="urgency"]:checked')).map(cb => cb.value);
    const tagFilters = Array.from(document.querySelectorAll('input[name="tags"]:checked')).map(cb => cb.value);
    const searchQuery = document.getElementById('search').value.toLowerCase();

    const requestItems = document.querySelectorAll('.map-request-item');
    let visibleCount = 0;

    requestItems.forEach(item => {
        const helpType = item.dataset.helpType;
        const urgency = item.dataset.urgency;
        const tags = item.dataset.tags ? item.dataset.tags.split(',') : [];
        const title = item.querySelector('h4').textContent.toLowerCase();
        const description = item.querySelector('p').textContent.toLowerCase();

        let show = true;

        // Help type filter
        if (helpTypeFilters.length > 0 && !helpTypeFilters.includes(helpType)) {
            show = false;
        }

        // Urgency filter
        if (urgencyFilters.length > 0 && !urgencyFilters.includes(urgency)) {
            show = false;
        }

        // Tag filter
        if (tagFilters.length > 0) {
            const hasMatchingTag = tagFilters.some(tagId => 
                tags.some(tag => tag.trim() === document.querySelector(`input[name="tags"][value="${tagId}"]`).nextElementSibling.textContent.trim())
            );
            if (!hasMatchingTag) {
                show = false;
            }
        }

        // Search filter
        if (searchQuery && !title.includes(searchQuery) && !description.includes(searchQuery)) {
            show = false;
        }

        item.style.display = show ? 'block' : 'none';
        if (show) visibleCount++;
    });

    document.getElementById('requests-count').textContent = visibleCount;
    
    // Update markers
    addRequestMarkers();
}
</script>

<style>
.custom-marker {
    background: transparent;
    border: none;
}

.user-location-marker {
    background: transparent;
    border: none;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.leaflet-popup-content {
    margin: 8px 12px;
}

.leaflet-popup-content-wrapper {
    border-radius: 8px;
}
</style>

<?php include __DIR__ .'../includes/footer.php'; ?>
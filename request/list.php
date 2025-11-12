<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = "Daftar Permintaan Bantuan - Bantuin Yuk";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';


// Get filters from URL
$tag_filter = isset($_GET['tag']) ? intval($_GET['tag']) : null;
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'open';
$urgency_filter = isset($_GET['urgency']) ? $_GET['urgency'] : null;
$search_query = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Build query with filters
$filters = [];
if ($tag_filter) {
    $filters['tags'] = [$tag_filter];
}
if ($status_filter && $status_filter !== 'all') {
    $filters['status'] = $status_filter;
}

$requests = getRequests($filters);
$all_tags = getAllTags();

// Filter by search query if provided
if (!empty($search_query)) {
    $requests = array_filter($requests, function($request) use ($search_query) {
        return stripos($request['title'], $search_query) !== false || 
               stripos($request['description'], $search_query) !== false ||
               stripos($request['user_name'], $search_query) !== false;
    });
}

// Filter by urgency if provided
if ($urgency_filter) {
    $requests = array_filter($requests, function($request) use ($urgency_filter) {
        return $request['urgency'] === $urgency_filter;
    });
}
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 animate-fade-in">
                        Daftar Permintaan Bantuan
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Temukan permintaan bantuan dari komunitas atau tawarkan bantuan Anda
                    </p>
                </div>
                <div class="mt-4 flex lg:mt-0 lg:ml-4">
                    <?php if (isLoggedIn()): ?>
                        <a href="create.php" 
                           class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Permintaan
                        </a>
                    <?php else: ?>
                        <a href="../auth/login.php" 
                           class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk untuk Membuat Permintaan
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="px-4 pb-6 sm:px-0">
            <div class="bg-white rounded-lg shadow p-6 mb-6 animate-slide-up">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="relative">
                            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search_query); ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Cari permintaan...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>Semua Status</option>
                            <option value="open" <?php echo $status_filter === 'open' ? 'selected' : ''; ?>>Terbuka</option>
                            <option value="in_progress" <?php echo $status_filter === 'in_progress' ? 'selected' : ''; ?>>Dalam Proses</option>
                            <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>

                    <!-- Urgency Filter -->
                    <div>
                        <label for="urgency" class="block text-sm font-medium text-gray-700 mb-1">Urgensi</label>
                        <select id="urgency" name="urgency" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">Semua Urgensi</option>
                            <option value="low" <?php echo $urgency_filter === 'low' ? 'selected' : ''; ?>>Rendah</option>
                            <option value="medium" <?php echo $urgency_filter === 'medium' ? 'selected' : ''; ?>>Sedang</option>
                            <option value="high" <?php echo $urgency_filter === 'high' ? 'selected' : ''; ?>>Tinggi</option>
                            <option value="critical" <?php echo $urgency_filter === 'critical' ? 'selected' : ''; ?>>Kritis</option>
                        </select>
                    </div>

                    <!-- Tag Filter -->
                    <div>
                        <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="tag" name="tag" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">Semua Kategori</option>
                            <?php foreach($all_tags as $tag): ?>
                                <option value="<?php echo $tag['id']; ?>" <?php echo $tag_filter == $tag['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" id="apply-filters" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-filter mr-2"></i>
                        Terapkan Filter
                    </button>
                    
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold"><?php echo count($requests); ?></span> permintaan
                    </div>
                </div>
            </div>

            <!-- Requests Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php if (!empty($requests)): ?>
                    <?php foreach($requests as $request): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 animate-fade-in searchable-item filterable-item"
                             data-category="<?php echo $request['status']; ?>">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-<?php echo getUrgencyColor($request['urgency']); ?>-500 to-<?php echo getUrgencyColor($request['urgency']); ?>-600 px-4 py-3">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-white line-clamp-2">
                                            <?php echo htmlspecialchars($request['title']); ?>
                                        </h3>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white bg-opacity-20 text-white mr-2">
                                                <i class="fas fa-<?php echo getUrgencyIcon($request['urgency']); ?> mr-1"></i>
                                                <?php echo ucfirst($request['urgency']); ?>
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white bg-opacity-20 text-white">
                                                <i class="fas fa-<?php echo $request['help_type'] === 'request' ? 'hands-helping' : 'heart'; ?> mr-1"></i>
                                                <?php echo $request['help_type'] === 'request' ? 'Butuh Bantuan' : 'Menawarkan Bantuan'; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    <?php echo htmlspecialchars($request['description']); ?>
                                </p>

                                <!-- Tags -->
                                <?php if ($request['tag_names']): ?>
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        <?php 
                                        $tags = explode(',', $request['tag_names']);
                                        foreach(array_slice($tags, 0, 3) as $tag): 
                                        ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo htmlspecialchars(trim($tag)); ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($tags) > 3): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                +<?php echo count($tags) - 3; ?> lagi
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Meta Information -->
                                <div class="space-y-2 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2"></i>
                                        <span><?php echo htmlspecialchars($request['user_name']); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <span><?php echo htmlspecialchars($request['location']); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span><?php echo time_elapsed_string($request['created_at']); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo $request['status'] == 'open' ? 'bg-green-100 text-green-800' : 
                                              ($request['status'] == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                              'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                    
                                    <a href="view.php?id=<?php echo $request['id']; ?>" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        Lihat Detail
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12 animate-fade-in">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada permintaan ditemukan</h3>
                        <p class="text-gray-500 mb-6">Coba ubah filter pencarian atau buat permintaan bantuan pertama.</p>
                        <?php if (isLoggedIn()): ?>
                            <a href="create.php" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-plus mr-2"></i>
                                Buat Permintaan Pertama
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const applyFiltersBtn = document.getElementById('apply-filters');
    
    applyFiltersBtn.addEventListener('click', function() {
        const search = document.getElementById('search').value;
        const status = document.getElementById('status').value;
        const urgency = document.getElementById('urgency').value;
        const tag = document.getElementById('tag').value;
        
        let url = 'list.php?';
        const params = [];
        
        if (search) params.push('search=' + encodeURIComponent(search));
        if (status && status !== 'all') params.push('status=' + status);
        if (urgency) params.push('urgency=' + urgency);
        if (tag) params.push('tag=' + tag);
        
        window.location.href = url + params.join('&');
    });

    // Enter key in search
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFiltersBtn.click();
        }
    });
});
</script>

<?php
// Helper functions
function getUrgencyColor($urgency) {
    switch ($urgency) {
        case 'low': return 'green';
        case 'medium': return 'blue';
        case 'high': return 'orange';
        case 'critical': return 'red';
        default: return 'gray';
    }
}

function getUrgencyIcon($urgency) {
    switch ($urgency) {
        case 'low': return 'clock';
        case 'medium': return 'exclamation';
        case 'high': return 'exclamation-circle';
        case 'critical': return 'skull-crossbones';
        default: return 'question';
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' lalu' : 'baru saja';
}
?>

<?php include __DIR__ .'../includes/footer.php'; ?>
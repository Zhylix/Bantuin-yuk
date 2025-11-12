<?php
require_once  __DIR__ .'../includes/config.php';
require_once __DIR__ .'../includes/functions.php';

// Check if user is logged in and is a regular user
if (!isLoggedIn() || isAdmin()) {
    redirect('/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

// Get user's requests
$sql = "SELECT r.*, GROUP_CONCAT(t.name) as tag_names 
        FROM requests r 
        LEFT JOIN request_tags rt ON r.id = rt.request_id 
        LEFT JOIN tags t ON rt.tag_id = t.id 
        WHERE r.user_id = ? 
        GROUP BY r.id 
        ORDER BY r.created_at DESC 
        LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_requests = $stmt->get_result();

// Get recent requests from others
$sql = "SELECT r.*, u.name as user_name, GROUP_CONCAT(t.name) as tag_names 
        FROM requests r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN request_tags rt ON r.id = rt.request_id 
        LEFT JOIN tags t ON rt.tag_id = t.id 
        WHERE r.user_id != ? AND r.status = 'open'
        GROUP BY r.id 
        ORDER BY r.created_at DESC 
        LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_requests = $stmt->get_result();

// Get user stats
$stats_sql = "SELECT 
    COUNT(*) as total_requests,
    SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open_requests,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_requests
    FROM requests WHERE user_id = ?";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("i", $user_id);
$stats_stmt->execute();
$stats_result = $stats_stmt->get_result();
$stats = $stats_result->fetch_assoc();

$page_title = "Dashboard - Bantuin Yuk";
include '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 animate-fade-in">
                        Selamat Datang, <?php echo htmlspecialchars($user['name']); ?>!
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Mari bantu sesama yang membutuhkan atau dapatkan bantuan untuk kebutuhan Anda.
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="../request/create.php" 
                       class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Permintaan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 animate-slide-up">
                <!-- Total Requests -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-hands-helping text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Permintaan</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo $stats['total_requests'] ?? 0; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="requests.php" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                                Lihat semua
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Open Requests -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Permintaan Terbuka</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo $stats['open_requests'] ?? 0; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-yellow-600 font-medium">Perlu perhatian</span>
                        </div>
                    </div>
                </div>

                <!-- Completed Requests -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Selesai</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo $stats['completed_requests'] ?? 0; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-green-600 font-medium">Berhasil dibantu</span>
                        </div>
                    </div>
                </div>

                <!-- Help Provided -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-heart text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Bantuan Diberikan</dt>
                                    <dd class="text-lg font-medium text-gray-900">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-purple-600 font-medium">Kontribusi Anda</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col lg:flex-row gap-8">
            <!-- My Recent Requests -->
            <div class="lg:w-2/3">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up" style="animation-delay: 0.1s">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <i class="fas fa-list mr-2 text-blue-500"></i>
                            Permintaan Bantuan Saya
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Daftar permintaan bantuan yang telah Anda buat.
                        </p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php if ($user_requests->num_rows > 0): ?>
                            <?php while($request = $user_requests->fetch_assoc()): ?>
                                <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                                <?php echo htmlspecialchars($request['title']); ?>
                                            </p>
                                            <span class="ml-2 flex-shrink-0 flex">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php echo $request['status'] == 'open' ? 'bg-green-100 text-green-800' : 
                                                          ($request['status'] == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                          'bg-gray-100 text-gray-800'); ?>">
                                                    <?php echo ucfirst($request['status']); ?>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="text-sm text-gray-500">
                                                <?php echo date('d M Y', strtotime($request['created_at'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i>
                                                <?php echo htmlspecialchars($request['location']); ?>
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <?php if ($request['tag_names']): ?>
                                                <?php 
                                                $tags = explode(',', $request['tag_names']);
                                                foreach(array_slice($tags, 0, 2) as $tag): 
                                                ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                        <?php echo htmlspecialchars($tag); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                                <?php if (count($tags) > 2): ?>
                                                    <span class="text-xs text-gray-400">+<?php echo count($tags) - 2; ?> lebih</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900">Belum ada permintaan</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulailah dengan membuat permintaan bantuan pertama Anda.</p>
                                <div class="mt-6">
                                    <a href="../request/create.php" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                        <i class="fas fa-plus mr-2"></i>
                                        Buat Permintaan Baru
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6">
                        <div class="text-sm">
                            <a href="requests.php" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                                Lihat semua permintaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Help Requests -->
            <div class="lg:w-1/3">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up" style="animation-delay: 0.2s">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <i class="fas fa-users mr-2 text-green-500"></i>
                            Permintaan Bantuan Terbaru
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Orang-orang di sekitar Anda membutuhkan bantuan.
                        </p>
                    </div>
                    <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        <?php if ($recent_requests->num_rows > 0): ?>
                            <?php while($request = $recent_requests->fetch_assoc()): ?>
                                <li class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-hands-helping text-blue-600 text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($request['user_name']); ?>
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">
                                                <?php echo htmlspecialchars($request['title']); ?>
                                            </p>
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                <?php if ($request['tag_names']): ?>
                                                    <?php 
                                                    $tags = explode(',', $request['tag_names']);
                                                    foreach(array_slice($tags, 0, 2) as $tag): 
                                                    ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            <?php echo htmlspecialchars($tag); ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 self-center flex">
                                            <a href="../request/view.php?id=<?php echo $request['id']; ?>" 
                                               class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="px-4 py-8 text-center">
                                <i class="fas fa-users text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-500">Belum ada permintaan bantuan terbaru.</p>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6">
                        <div class="text-sm">
                            <a href="../request/list.php" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                                Jelajahi semua permintaan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up" style="animation-delay: 0.3s">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="px-4 py-6">
                        <div class="grid grid-cols-1 gap-4">
                            <a href="../pages/map.php" 
                               class="group p-4 border-2 border-gray-200 border-dashed rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-map-marked-alt text-blue-500 text-xl group-hover:text-blue-600 transition-colors duration-200"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-blue-700 transition-colors duration-200">
                                            Lihat Peta Bantuan
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Temukan permintaan bantuan di sekitar Anda
                                        </p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="../request/create.php" 
                               class="group p-4 border-2 border-gray-200 border-dashed rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-plus-circle text-green-500 text-xl group-hover:text-green-600 transition-colors duration-200"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-green-700 transition-colors duration-200">
                                            Buat Permintaan Baru
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Posting kebutuhan bantuan Anda
                                        </p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="profile.php" 
                               class="group p-4 border-2 border-gray-200 border-dashed rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all duration-200 transform hover:scale-105">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-edit text-purple-500 text-xl group-hover:text-purple-600 transition-colors duration-200"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-purple-700 transition-colors duration-200">
                                            Edit Profil
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Perbarui informasi akun Anda
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ .'../includes/footer.php'; ?>
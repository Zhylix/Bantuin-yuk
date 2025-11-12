<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('/auth/login.php');
}

// Get statistics
$stats_sql = "
    SELECT 
        (SELECT COUNT(*) FROM users WHERE role = 'user') as total_users,
        (SELECT COUNT(*) FROM requests) as total_requests,
        (SELECT COUNT(*) FROM requests WHERE status = 'open') as open_requests,
        (SELECT COUNT(*) FROM requests WHERE status = 'completed') as completed_requests,
        (SELECT COUNT(*) FROM help_responses) as total_responses,
        (SELECT COUNT(*) FROM tags) as total_tags
";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();

// Get recent requests
$recent_requests_sql = "
    SELECT r.*, u.name as user_name 
    FROM requests r 
    LEFT JOIN users u ON r.user_id = u.id 
    ORDER BY r.created_at DESC 
    LIMIT 5
";
$recent_requests = $conn->query($recent_requests_sql);

// Get recent users
$recent_users_sql = "
    SELECT * FROM users 
    WHERE role = 'user' 
    ORDER BY created_at DESC 
    LIMIT 5
";
$recent_users = $conn->query($recent_users_sql);

$page_title = "Admin Dashboard - Bantuin Yuk";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 animate-fade-in">
                        Admin Dashboard
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Kelola platform Bantuin Yuk dan pantau aktivitas pengguna
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <a href="users.php" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-users mr-2"></i>
                        Kelola Pengguna
                    </a>
                    <a href="requests.php" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-list mr-2"></i>
                        Kelola Permintaan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 animate-slide-up">
                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo $stats['total_users']; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="users.php" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                                Lihat semua
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Requests -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-hands-helping text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Permintaan</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo $stats['total_requests']; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="requests.php" class="font-medium text-green-600 hover:text-green-500 transition-colors duration-200">
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
                                    <dd class="text-lg font-medium text-gray-900"><?php echo $stats['open_requests']; ?></dd>
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

                <!-- Total Responses -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-comments text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Tanggapan</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo $stats['total_responses']; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-purple-600 font-medium">Aktivitas bantuan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Requests -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up" style="animation-delay: 0.1s">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                        <i class="fas fa-list mr-2 text-blue-500"></i>
                        Permintaan Terbaru
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Daftar permintaan bantuan yang baru dibuat
                    </p>
                </div>
                <div class="divide-y divide-gray-200">
                    <?php if ($recent_requests->num_rows > 0): ?>
                        <?php while($request = $recent_requests->fetch_assoc()): ?>
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
                                            <?php echo time_elapsed_string($request['created_at']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-user mr-1.5 text-gray-400"></i>
                                        <?php echo htmlspecialchars($request['user_name']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="px-4 py-8 text-center">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-500">Belum ada permintaan bantuan.</p>
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

            <!-- Recent Users -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up" style="animation-delay: 0.2s">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                        <i class="fas fa-users mr-2 text-green-500"></i>
                        Pengguna Terbaru
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Pengguna yang baru bergabung dengan platform
                    </p>
                </div>
                <ul class="divide-y divide-gray-200">
                    <?php if ($recent_users->num_rows > 0): ?>
                        <?php while($user = $recent_users->fetch_assoc()): ?>
                            <li class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?php echo htmlspecialchars($user['name']); ?>
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-sm text-gray-500">
                                        <?php echo time_elapsed_string($user['created_at']); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="px-4 py-8 text-center">
                            <i class="fas fa-users text-3xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-500">Belum ada pengguna terdaftar.</p>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="bg-gray-50 px-4 py-3 sm:px-6">
                    <div class="text-sm">
                        <a href="users.php" class="font-medium text-green-600 hover:text-green-500 transition-colors duration-200">
                            Lihat semua pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up" style="animation-delay: 0.3s">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="px-4 py-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="tags.php" 
                       class="group p-6 border-2 border-gray-200 border-dashed rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-tags text-purple-500 text-2xl group-hover:text-purple-600 transition-colors duration-200"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900 group-hover:text-purple-700 transition-colors duration-200">
                                    Kelola Kategori
                                </p>
                                <p class="text-sm text-gray-500">
                                    Tambah/edit kategori bantuan
                                </p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="requests.php?status=open" 
                       class="group p-6 border-2 border-gray-200 border-dashed rounded-lg hover:border-red-300 hover:bg-red-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-500 text-2xl group-hover:text-red-600 transition-colors duration-200"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900 group-hover:text-red-700 transition-colors duration-200">
                                    Permintaan Mendesak
                                </p>
                                <p class="text-sm text-gray-500">
                                    Lihat permintaan dengan urgensi tinggi
                                </p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="users.php" 
                       class="group p-6 border-2 border-gray-200 border-dashed rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-cog text-blue-500 text-2xl group-hover:text-blue-600 transition-colors duration-200"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900 group-hover:text-blue-700 transition-colors duration-200">
                                    Kelola Pengguna
                                </p>
                                <p class="text-sm text-gray-500">
                                    Aktivasi/non-aktivasi akun
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
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

<?php include '../includes/footer.php'; ?>
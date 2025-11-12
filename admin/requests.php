<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('/auth/login.php');
}

// Get all requests with user information
$sql = "SELECT r.*, u.name as user_name, u.email as user_email 
        FROM requests r 
        LEFT JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC";
$requests = $conn->query($sql);

$page_title = "Kelola Permintaan - Bantuin Yuk";
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
                        Kelola Permintaan
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Kelola semua permintaan bantuan di platform Bantuin Yuk
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="dashboard.php" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 sm:px-0">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 animate-pulse" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 animate-shake" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white shadow overflow-hidden sm:rounded-md animate-slide-up">
                <?php if ($requests->num_rows > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <?php while($request = $requests->fetch_assoc()): ?>
                            <li class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-<?php echo getUrgencyColor($request['urgency']); ?>-500 rounded-full flex items-center justify-center text-white">
                                                <i class="fas fa-<?php echo $request['help_type'] === 'request' ? 'hands-helping' : 'heart'; ?>"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($request['title']); ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                oleh <?php echo htmlspecialchars($request['user_name']); ?>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">
                                                <?php echo htmlspecialchars($request['location']); ?> • 
                                                <?php echo time_elapsed_string($request['created_at']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            <?php echo $request['status'] == 'open' ? 'bg-green-100 text-green-800' : 
                                                  ($request['status'] == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                  'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo ucfirst($request['status']); ?>
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            bg-<?php echo getUrgencyColor($request['urgency']); ?>-100 text-<?php echo getUrgencyColor($request['urgency']); ?>-800">
                                            <?php echo ucfirst($request['urgency']); ?>
                                        </span>
                                        <div class="flex space-x-2">
                                            <a href="../request/view.php?id=<?php echo $request['id']; ?>" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="../process/request_process.php" class="inline">
                                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?')"
                                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada permintaan</h3>
                        <p class="text-gray-500">Tidak ada permintaan bantuan yang dibuat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php
function getUrgencyColor($urgency) {
    switch ($urgency) {
        case 'low': return 'green';
        case 'medium': return 'blue';
        case 'high': return 'orange';
        case 'critical': return 'red';
        default: return 'gray';
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

<?php include '../includes/footer.php'; ?>
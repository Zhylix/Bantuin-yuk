<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

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
        ORDER BY r.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_requests = $stmt->get_result();

$page_title = "Permintaan Saya - Bantuin Yuk";
include '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 animate-fade-in">
                        Permintaan Bantuan Saya
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Kelola semua permintaan bantuan yang telah Anda buat.
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
        <div class="px-4 sm:px-0">
            <?php if ($user_requests->num_rows > 0): ?>
                <div class="bg-white shadow overflow-hidden sm:rounded-md animate-slide-up">
                    <ul class="divide-y divide-gray-200">
                        <?php while($request = $user_requests->fetch_assoc()): ?>
                            <li class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-hands-helping text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <h4 class="text-lg font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($request['title']); ?>
                                                </h4>
                                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php echo $request['status'] == 'open' ? 'bg-green-100 text-green-800' : 
                                                          ($request['status'] == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                          'bg-gray-100 text-gray-800'); ?>">
                                                    <?php echo ucfirst($request['status']); ?>
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <?php echo htmlspecialchars($request['location']); ?>
                                            </p>
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                <?php if ($request['tag_names']): ?>
                                                    <?php 
                                                    $tags = explode(',', $request['tag_names']);
                                                    foreach($tags as $tag): 
                                                    ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <?php echo htmlspecialchars($tag); ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-500">
                                            <?php echo time_elapsed_string($request['created_at']); ?>
                                        </span>
                                        <div class="flex space-x-2">
                                            <a href="../request/view.php?id=<?php echo $request['id']; ?>" 
                                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat
                                            </a>
                                            <?php if ($request['status'] == 'open'): ?>
                                                <form action="../process/request_process.php" method="POST" class="inline">
                                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                    <input type="hidden" name="action" value="update_status">
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-green-600 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                                            onclick="return confirm('Tandai permintaan sebagai selesai?')">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Selesai
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <form action="../process/request_process.php" method="POST" class="inline">
                                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-red-600 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                        onclick="return confirm('Hapus permintaan ini? Tindakan ini tidak dapat dibatalkan.')">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md p-12 text-center animate-fade-in">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-medium text-gray-900 mb-4">Belum ada permintaan</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">
                        Anda belum membuat permintaan bantuan. Mulailah dengan membuat permintaan pertama Anda.
                    </p>
                    <a href="../request/create.php" 
                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Permintaan Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
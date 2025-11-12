<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('/request/list.php');
}

$request_id = intval($_GET['id']);

// Get request details
$sql = "SELECT r.*, u.name as user_name, u.email, u.phone, 
               GROUP_CONCAT(t.name) as tag_names, GROUP_CONCAT(t.id) as tag_ids,
               GROUP_CONCAT(t.color) as tag_colors
        FROM requests r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN request_tags rt ON r.id = rt.request_id 
        LEFT JOIN tags t ON rt.tag_id = t.id 
        WHERE r.id = ? 
        GROUP BY r.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('/request/list.php');
}

$request = $result->fetch_assoc();

// Get help responses
$response_sql = "SELECT hr.*, u.name as helper_name, u.email as helper_email
                 FROM help_responses hr
                 LEFT JOIN users u ON hr.helper_id = u.id
                 WHERE hr.request_id = ? 
                 ORDER BY hr.created_at DESC";
$response_stmt = $conn->prepare($response_sql);
$response_stmt->bind_param("i", $request_id);
$response_stmt->execute();
$responses = $response_stmt->get_result();

$page_title = $request['title'] . " - Bantuin Yuk";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Request Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-<?php echo getUrgencyColor($request['urgency']); ?>-500 to-<?php echo getUrgencyColor($request['urgency']); ?>-600 px-6 py-4">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h1 class="text-2xl font-bold text-white mr-3"><?php echo htmlspecialchars($request['title']); ?></h1>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-white bg-opacity-20 text-white">
                                <i class="fas fa-<?php echo getUrgencyIcon($request['urgency']); ?> mr-1"></i>
                                <?php echo ucfirst($request['urgency']); ?>
                            </span>
                        </div>
                        <p class="text-<?php echo getUrgencyColor($request['urgency']); ?>-100 flex items-center">
                            <i class="fas fa-<?php echo $request['help_type'] === 'request' ? 'hands-helping' : 'heart'; ?> mr-2"></i>
                            <?php echo $request['help_type'] === 'request' ? 'Meminta Bantuan' : 'Menawarkan Bantuan'; ?>
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                            <?php echo $request['status'] == 'open' ? 'bg-green-100 text-green-800' : 
                                  ($request['status'] == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                  'bg-gray-100 text-gray-800'); ?>">
                            <?php echo ucfirst($request['status']); ?>
                        </span>
                        <?php if (isLoggedIn() && ($_SESSION['user_id'] == $request['user_id'] || isAdmin())): ?>
                            <a href="#" class="text-white hover:text-gray-200 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Deskripsi Permintaan
                    </h3>
                    <div class="prose max-w-none text-gray-700 bg-gray-50 rounded-lg p-4">
                        <?php echo nl2br(htmlspecialchars($request['description'])); ?>
                    </div>
                </div>

                <!-- Tags -->
                <?php if ($request['tag_names']): ?>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-tags mr-2 text-purple-500"></i>
                            Kategori
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <?php 
                            $tags = explode(',', $request['tag_names']);
                            $tag_ids = explode(',', $request['tag_ids']);
                            $tag_colors = explode(',', $request['tag_colors']);
                            
                            for ($i = 0; $i < count($tags); $i++): 
                                $tag = trim($tags[$i]);
                                $color = isset($tag_colors[$i]) ? trim($tag_colors[$i]) : '#3B82F6';
                            ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" 
                                      style="background-color: <?php echo $color; ?>">
                                    <?php echo htmlspecialchars($tag); ?>
                                </span>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Requester Info -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Informasi Pemohon
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-blue-800">
                                <i class="fas fa-user-circle mr-2 w-5"></i>
                                <span><?php echo htmlspecialchars($request['user_name']); ?></span>
                            </div>
                            <div class="flex items-center text-blue-800">
                                <i class="fas fa-envelope mr-2 w-5"></i>
                                <span><?php echo htmlspecialchars($request['email']); ?></span>
                            </div>
                            <?php if ($request['phone']): ?>
                                <div class="flex items-center text-blue-800">
                                    <i class="fas fa-phone mr-2 w-5"></i>
                                    <span><?php echo htmlspecialchars($request['phone']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Request Details -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Detail Permintaan
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-green-800">
                                <i class="fas fa-map-marker-alt mr-2 w-5"></i>
                                <span><?php echo htmlspecialchars($request['location']); ?></span>
                            </div>
                            <div class="flex items-center text-green-800">
                                <i class="fas fa-clock mr-2 w-5"></i>
                                <span>Dibuat <?php echo time_elapsed_string($request['created_at']); ?></span>
                            </div>
                            <?php if ($request['deadline']): ?>
                                <div class="flex items-center text-green-800">
                                    <i class="fas fa-calendar-alt mr-2 w-5"></i>
                                    <span>Batas waktu: <?php echo date('d M Y', strtotime($request['deadline'])); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <?php if (isLoggedIn() && $_SESSION['user_id'] != $request['user_id'] && $request['status'] == 'open'): ?>
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-hand-holding-heart mr-2 text-red-500"></i>
                            Tertarik Membantu?
                        </h3>
                        <button type="button" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-md"
                                onclick="openResponseModal()">
                            <i class="fas fa-heart mr-2"></i>
                            Tawarkan Bantuan
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Responses Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden animate-slide-up">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-comments mr-2 text-green-500"></i>
                    Tanggapan Bantuan
                    <span class="ml-2 bg-green-100 text-green-800 text-sm px-2 py-1 rounded-full">
                        <?php echo $responses->num_rows; ?>
                    </span>
                </h2>
            </div>

            <div class="p-6">
                <?php if ($responses->num_rows > 0): ?>
                    <div class="space-y-4">
                        <?php while($response = $responses->fetch_assoc()): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($response['helper_name']); ?></h4>
                                            <p class="text-sm text-gray-500"><?php echo time_elapsed_string($response['created_at']); ?></p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo $response['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                              ($response['status'] == 'accepted' ? 'bg-green-100 text-green-800' : 
                                              ($response['status'] == 'rejected' ? 'bg-red-100 text-red-800' : 
                                              'bg-gray-100 text-gray-800')); ?>">
                                        <?php echo ucfirst($response['status']); ?>
                                    </span>
                                </div>
                                <p class="text-gray-700 mb-3"><?php echo nl2br(htmlspecialchars($response['message'])); ?></p>
                                <?php if ($response['status'] == 'accepted' && $response['rating']): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="mr-2">Rating:</span>
                                        <div class="flex text-yellow-400">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star<?php echo $i <= $response['rating'] ? '' : '-o'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <?php if ($response['review']): ?>
                                            <span class="ml-2">- "<?php echo htmlspecialchars($response['review']); ?>"</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-comment-slash text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada tanggapan</h3>
                        <p class="text-gray-500">Jadilah yang pertama menawarkan bantuan untuk permintaan ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Response Modal -->
<div id="response-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white animate-slide-up">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-heart text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Tawarkan Bantuan</h3>
            <p class="text-sm text-gray-500 text-center mb-4">
                Kirim pesan kepada <?php echo htmlspecialchars($request['user_name']); ?> untuk menawarkan bantuan Anda.
            </p>
            
            <form id="response-form" action="../process/response_process.php" method="POST">
                <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                <div class="mb-4">
                    <label for="response_message" class="block text-sm font-medium text-gray-700 mb-2">Pesan Anda</label>
                    <textarea id="response_message" name="message" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                              placeholder="Tuliskan bagaimana Anda bisa membantu..."></textarea>
                </div>
                
                <div class="flex items-center justify-between mt-4">
                    <button type="button" 
                            onclick="closeResponseModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-1/3 shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-1/3 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition-all duration-200 transform hover:scale-105">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResponseModal() {
    document.getElementById('response-modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeResponseModal() {
    document.getElementById('response-modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.getElementById('response-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResponseModal();
    }
});

// Handle form submission
document.getElementById('response-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeResponseModal();
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengirim tanggapan', 'error');
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

<?php include __DIR__ . '../includes/footer.php'; ?>
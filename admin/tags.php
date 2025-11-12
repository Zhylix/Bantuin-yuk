<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('/auth/login.php');
}

// Get all tags
$tags = getAllTags();

$page_title = "Kelola Kategori - Bantuin Yuk";
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
                        Kelola Kategori
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Kelola kategori bantuan yang tersedia di platform
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <button type="button" 
                            onclick="openAddTagModal()"
                            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Kategori
                    </button>
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
                <?php if (!empty($tags)): ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach($tags as $tag): ?>
                            <li class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white"
                                                 style="background-color: <?php echo $tag['color']; ?>">
                                                <?php if ($tag['icon']): ?>
                                                    <i class="<?php echo $tag['icon']; ?>"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-tag"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($tag['name']); ?>
                                            </div>
                                            <?php if ($tag['description']): ?>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($tag['description']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            <?php echo $tag['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo $tag['is_active'] ? 'Aktif' : 'Non-aktif'; ?>
                                        </span>
                                        <div class="flex space-x-2">
                                            <button type="button" 
                                                    onclick="openEditTagModal(<?php echo htmlspecialchars(json_encode($tag)); ?>)"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="../process/tag_process.php" class="inline">
                                                <input type="hidden" name="tag_id" value="<?php echo $tag['id']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kategori</h3>
                        <p class="text-gray-500">Mulai dengan menambahkan kategori bantuan pertama.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<!-- Add Tag Modal -->
<div id="add-tag-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white animate-slide-up">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-plus text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Tambah Kategori Baru</h3>
            
            <form id="add-tag-form" action="../process/tag_process.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="mb-4">
                    <label for="tag_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" id="tag_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="Contoh: Kesehatan">
                </div>
                
                <div class="mb-4">
                    <label for="tag_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="tag_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                              placeholder="Deskripsi singkat kategori"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="tag_color" class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                    <input type="color" id="tag_color" name="color" value="#3B82F6" required
                           class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>
                
                <div class="mb-4">
                    <label for="tag_icon" class="block text-sm font-medium text-gray-700 mb-2">Ikon (Font Awesome)</label>
                    <input type="text" id="tag_icon" name="icon"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="Contoh: fa-heartbeat">
                    <p class="text-xs text-gray-500 mt-1">Gunakan nama kelas Font Awesome tanpa 'fa-'</p>
                </div>
                
                <div class="flex items-center justify-between mt-4">
                    <button type="button" 
                            onclick="closeAddTagModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-1/3 shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-1/3 shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tag Modal -->
<div id="edit-tag-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white animate-slide-up">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <i class="fas fa-edit text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Edit Kategori</h3>
            
            <form id="edit-tag-form" action="../process/tag_process.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_tag_id" name="tag_id">
                <div class="mb-4">
                    <label for="edit_tag_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" id="edit_tag_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                </div>
                
                <div class="mb-4">
                    <label for="edit_tag_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="edit_tag_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 resize-none"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="edit_tag_color" class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                    <input type="color" id="edit_tag_color" name="color" required
                           class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                </div>
                
                <div class="mb-4">
                    <label for="edit_tag_icon" class="block text-sm font-medium text-gray-700 mb-2">Ikon (Font Awesome)</label>
                    <input type="text" id="edit_tag_icon" name="icon"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                           placeholder="Contoh: fa-heartbeat">
                    <p class="text-xs text-gray-500 mt-1">Gunakan nama kelas Font Awesome tanpa 'fa-'</p>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="edit_tag_active" name="is_active" value="1" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>
                </div>
                
                <div class="flex items-center justify-between mt-4">
                    <button type="button" 
                            onclick="closeEditTagModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-1/3 shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-1/3 shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition-all duration-200 transform hover:scale-105">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddTagModal() {
    document.getElementById('add-tag-modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAddTagModal() {
    document.getElementById('add-tag-modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openEditTagModal(tag) {
    document.getElementById('edit_tag_id').value = tag.id;
    document.getElementById('edit_tag_name').value = tag.name;
    document.getElementById('edit_tag_description').value = tag.description || '';
    document.getElementById('edit_tag_color').value = tag.color;
    document.getElementById('edit_tag_icon').value = tag.icon ? tag.icon.replace('fa-', '') : '';
    document.getElementById('edit_tag_active').checked = tag.is_active;
    
    document.getElementById('edit-tag-modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeEditTagModal() {
    document.getElementById('edit-tag-modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modals when clicking outside
document.getElementById('add-tag-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddTagModal();
    }
});

document.getElementById('edit-tag-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditTagModal();
    }
});
</script>

<?php include '../includes/footer.php'; ?>
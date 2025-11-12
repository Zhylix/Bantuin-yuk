<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is a regular user
if (!isLoggedIn() || isAdmin()) {
    redirect('/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);

    $errors = [];

    // Validation
    if (empty($name)) {
        $errors[] = "Nama lengkap harus diisi";
    }

    if (empty($errors)) {
        $sql = "UPDATE users SET name = ?, phone = ?, address = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $phone, $address, $user_id);

        if ($stmt->execute()) {
            $_SESSION['name'] = $name;
            $_SESSION['success'] = "Profil berhasil diperbarui";
            redirect('/user/profile.php');
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat memperbarui profil";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}

$page_title = "Profil Saya - Bantuin Yuk";
include '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 animate-fade-in">
                        Profil Saya
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Kelola informasi profil dan pengaturan akun Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                                Informasi Profil
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Informasi pribadi dan detail kontak Anda.
                            </p>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-shake" role="alert">
                                    <span class="block sm:inline"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 animate-pulse" role="alert">
                                    <span class="block sm:inline"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                                </div>
                            <?php endif; ?>

                            <form action="" method="POST">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                        <input type="text" id="name" name="name" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                               value="<?php echo htmlspecialchars($user['name']); ?>"
                                               placeholder="Masukkan nama lengkap Anda">
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" id="email" name="email" disabled
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                               value="<?php echo htmlspecialchars($user['email']); ?>">
                                        <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                        <input type="tel" id="phone" name="phone"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                               placeholder="Contoh: 081234567890">
                                    </div>

                                    <div>
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                        <textarea id="address" name="address" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                                  placeholder="Masukkan alamat lengkap Anda"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-md">
                                            <i class="fas fa-save mr-2"></i>
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-8 animate-slide-up" style="animation-delay: 0.1s">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <i class="fas fa-lock mr-2 text-green-500"></i>
                                Ubah Password
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Pastikan password Anda kuat dan aman.
                            </p>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <form action="../process/change_password_process.php" method="POST">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                        <input type="password" id="current_password" name="current_password" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                               placeholder="Masukkan password saat ini">
                                    </div>

                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                        <input type="password" id="new_password" name="new_password" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                               placeholder="Minimal 6 karakter">
                                    </div>

                                    <div>
                                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                        <input type="password" id="confirm_password" name="confirm_password" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                               placeholder="Ketik ulang password baru">
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-md">
                                            <i class="fas fa-key mr-2"></i>
                                            Ubah Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Account Summary -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg animate-slide-up" style="animation-delay: 0.2s">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                                Ringkasan Akun
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Status Akun</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo $user['is_active'] ? 'Aktif' : 'Tidak Aktif'; ?></p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar-alt text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Bergabung Sejak</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo date('d M Y', strtotime($user['created_at'])); ?></p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-shield-alt text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Role</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo ucfirst($user['role']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-8 animate-slide-up" style="animation-delay: 0.3s">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                                Aksi Cepat
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-3">
                                <a href="dashboard.php" 
                                   class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <i class="fas fa-tachometer-alt text-blue-500 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-700">Dashboard</span>
                                </a>

                                <a href="requests.php" 
                                   class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                                    <i class="fas fa-list-alt text-green-500 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-700">Permintaan Saya</span>
                                </a>

                                <a href="../request/create.php" 
                                   class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                                    <i class="fas fa-plus-circle text-purple-500 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-700">Buat Permintaan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePassword() {
        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Password tidak sesuai');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    if (newPassword && confirmPassword) {
        newPassword.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);
    }
});
</script>

<?php include '../includes/footer.php'; ?>
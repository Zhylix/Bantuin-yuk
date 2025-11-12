<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default page title if not set
if (!isset($page_title)) {
    $page_title = "Bantuin Yuk - Platform Bantuan Sosial";
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_name = $is_logged_in ? $_SESSION['name'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/img/favicon.ico">
    
    <!-- Meta Tags -->
    <meta name="description" content="Platform Bantuin Yuk - Menghubungkan mereka yang membutuhkan bantuan dengan mereka yang ingin membantu">
    <meta name="keywords" content="bantuan, sosial, tolong menolong, komunitas, kesehatan, pendidikan">
    
    <style>
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        
        /* Loading animation */
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Loading Spinner -->
    <div id="loading" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-300">
        <div class="text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600">Memuat Bantuin Yuk...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="<?php echo BASE_URL; ?>/pages/home.php" class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Bantuin Yuk</span>
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-6 md:flex md:space-x-4">
                        <a href="<?php echo BASE_URL; ?>/pages/home.php" 
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-home mr-1"></i> Beranda
                        </a>
                        
                        <?php if ($is_logged_in): ?>
                            <a href="<?php echo BASE_URL; ?>/request/list.php" 
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-list mr-1"></i> Daftar Permintaan
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>/pages/map.php" 
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-map-marker-alt mr-1"></i> Peta Bantuan
                            </a>
                            
                            <?php if ($is_admin): ?>
                                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" 
                                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Admin Dashboard
                                </a>
                            <?php else: ?>
                                <a href="<?php echo BASE_URL; ?>/user/dashboard.php" 
                                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <a href="<?php echo BASE_URL; ?>/pages/about.php" 
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-info-circle mr-1"></i> Tentang
                        </a>
                    </div>
                </div>
                
                <!-- Right Navigation -->
                <div class="flex items-center">
                    <?php if ($is_logged_in): ?>
                        <!-- User Menu -->
                        <div class="hidden md:flex items-center space-x-4">
                            <a href="<?php echo BASE_URL; ?>/request/create.php" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 transform hover:scale-105">
                                <i class="fas fa-plus mr-1"></i> Minta Bantuan
                            </a>
                            
                            <div class="relative group">
                                <button class="flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none transition-colors duration-200">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold mr-2">
                                        <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                                    </div>
                                    <span><?php echo htmlspecialchars($user_name); ?></span>
                                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                                    <a href="<?php echo BASE_URL; ?>/user/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150">
                                        <i class="fas fa-user mr-2"></i> Profil Saya
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/user/requests.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150">
                                        <i class="fas fa-list-alt mr-2"></i> Permintaan Saya
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Auth Buttons -->
                        <div class="hidden md:flex items-center space-x-3">
                            <a href="<?php echo BASE_URL; ?>/auth/login.php" 
                               class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                Masuk
                            </a>
                            <a href="<?php echo BASE_URL; ?>/auth/register.php" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 transform hover:scale-105">
                                Daftar
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" 
                                class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="<?php echo BASE_URL; ?>/pages/home.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i> Beranda
                </a>
                
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo BASE_URL; ?>/request/list.php" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i> Daftar Permintaan
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>map.php" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-map-marker-alt mr-2"></i> Peta Bantuan
                    </a>
                    
                    <?php if ($is_admin): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i> Admin Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/user/dashboard.php" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                
                <a href="<?php echo BASE_URL; ?>/pages/about.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                    <i class="fas fa-info-circle mr-2"></i> Tentang
                </a>
                
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo BASE_URL; ?>/request/create.php" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i> Minta Bantuan
                    </a>
                    
                    <div class="border-t border-gray-200 pt-2">
                        <a href="<?php echo BASE_URL; ?>/user/profile.php" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-user mr-2"></i> Profil Saya
                        </a>
                        <a href="<?php echo BASE_URL; ?>/user/requests.php" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-list-alt mr-2"></i> Permintaan Saya
                        </a>
                        <a href="<?php echo BASE_URL; ?>/auth/logout.php" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </a>
                    </div>
                <?php else: ?>
                    <div class="border-t border-gray-200 pt-2">
                        <a href="<?php echo BASE_URL; ?>/auth/login.php" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                        </a>
                        <a href="<?php echo BASE_URL; ?>/auth/register.php" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-user-plus mr-2"></i> Daftar
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm"></div>
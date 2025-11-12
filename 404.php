<?php
require_once 'includes/config.php';
$page_title = "Halaman Tidak Ditemukan - Bantuin Yuk";
include 'includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full text-center animate-fade-in">
        <!-- Animated 404 -->
        <div class="relative mb-8">
            <div class="text-9xl font-bold text-gray-300 opacity-50">404</div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-6xl font-bold text-blue-600 animate-bounce">404</div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-xl p-8 transform -translate-y-4">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Halaman Tidak Ditemukan</h1>
            
            <p class="text-lg text-gray-600 mb-8">
                Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan, dihapus, atau Anda salah mengetik URL.
            </p>
            
            <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                <a href="<?php echo BASE_URL; ?>/pages/home.php" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
                
                <a href="javascript:history.back()" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali Sebelumnya
                </a>
            </div>
        </div>
        
        <!-- Additional Help -->
        <div class="mt-8 text-center">
            <p class="text-gray-500 mb-4">Butuh bantuan? Coba salah satu link berikut:</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="<?php echo BASE_URL; ?>/pages/home.php" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-compass mr-1"></i> Beranda
                </a>
                <a href="<?php echo BASE_URL; ?>/auth/login.php" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                </a>
                <a href="<?php echo BASE_URL; ?>/request/list.php" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-list mr-1"></i> Daftar Permintaan
                </a>
                <a href="<?php echo BASE_URL; ?>/pages/about.php" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-info-circle mr-1"></i> Tentang Kami
                </a>
            </div>
        </div>
        
        <!-- Search Box -->
        <div class="mt-8 max-w-md mx-auto">
            <div class="relative">
                <input type="text" 
                       placeholder="Cari sesuatu..." 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <button class="absolute right-2 top-2 bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Elements for Visual Interest -->
<div class="fixed top-10 left-10 w-8 h-8 bg-yellow-400 rounded-full opacity-20 animate-float"></div>
<div class="fixed top-20 right-20 w-12 h-12 bg-blue-400 rounded-full opacity-30 animate-float" style="animation-delay: 1s"></div>
<div class="fixed bottom-20 left-20 w-10 h-10 bg-green-400 rounded-full opacity-25 animate-float" style="animation-delay: 2s"></div>
<div class="fixed bottom-10 right-10 w-6 h-6 bg-purple-400 rounded-full opacity-20 animate-float" style="animation-delay: 1.5s"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some interactive elements to the 404 page
    const floatingElements = document.querySelectorAll('.fixed');
    
    floatingElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Search functionality
    const searchInput = document.querySelector('input[type="text"]');
    const searchButton = document.querySelector('button');
    
    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();
        if (query) {
            window.location.href = `<?php echo BASE_URL; ?>/request/list.php?search=${encodeURIComponent(query)}`;
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchButton.click();
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
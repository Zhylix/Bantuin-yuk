<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Bantuin Yuk - Platform Bantuan Sosial";
include '../includes/header.php';
?>

<!-- Hero Section with Animation -->
<section class="relative bg-gradient-to-r from-blue-600 via-purple-700 to-indigo-800 text-white py-20 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col md:flex-row items-center gap-10">
            <!-- Left Text Section -->
            <div class="md:w-1/2 animate-fade-in text-center md:text-left">
                <div class="inline-block bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-2 mb-6">
                    <span class="text-yellow-300 font-semibold">✨ Platform Bantuan Sosial Terpercaya</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                    Bersama Kita Bisa 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500 block">Membantu Sesama</span>
                </h1>
                <p class="text-lg md:text-xl mb-8 opacity-90 leading-relaxed max-w-xl">
                    Platform untuk menghubungkan mereka yang membutuhkan bantuan dengan mereka yang ingin membantu. 
                    Mari wujudkan kepedulian sosial dalam genggaman Anda.
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-6">
                    <?php if (!isLoggedIn()): ?>
                        <a href="../auth/register.php" 
                           class="group bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-3xl flex items-center gap-2">
                            <span>Daftar Sekarang</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="../auth/login.php" 
                           class="group bg-white/10 backdrop-blur-sm border-2 border-white/30 hover:bg-white hover:text-purple-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                            <span>Masuk</span>
                            <i class="fas fa-sign-in-alt group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo isAdmin() ? '../admin/dashboard.php' : '../user/dashboard.php'; ?>" 
                           class="group bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-3xl flex items-center gap-2">
                            <span>Dashboard Saya</span>
                            <i class="fas fa-tachometer-alt group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Stats Section -->
                <div class="flex flex-wrap gap-6 mt-10 justify-center md:justify-start">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-300">500+</div>
                        <div class="text-sm opacity-80">Bantuan Terpenuhi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-300">1.2K+</div>
                        <div class="text-sm opacity-80">Relawan Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-300">95%</div>
                        <div class="text-sm opacity-80">Kepuasan Pengguna</div>
                    </div>
                </div>
            </div>

            <!-- Right Graphic -->
            <div class="md:w-1/2 animate-float">
                <div class="relative">
                    <!-- Main Card -->
                    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-6 shadow-2xl transform rotate-1 hover:rotate-0 transition-all duration-500">
                        <div class="flex items-center mb-4">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                            <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                            <span class="w-3 h-3 bg-green-400 rounded-full"></span>
                        </div>
                        
                        <!-- Request Card -->
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-5 border border-white/10">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-4 shadow-lg">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white text-lg">Bantuan Kesehatan</h3>
                                    <p class="text-sm text-white/80">Butuh donor darah untuk operasi</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="bg-red-500/30 text-white px-3 py-1 rounded-full font-medium text-sm backdrop-blur-sm">Mendesak</span>
                                <span class="text-white/70 text-sm">2 jam lalu</span>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-white/80 mb-1">
                                <span>Terkumpul</span>
                                <span>Rp 2.5 Juta / Rp 5 Juta</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full w-1/2"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-5 -right-5 bg-gradient-to-br from-yellow-400 to-yellow-500 text-gray-900 p-4 rounded-xl shadow-2xl animate-bounce z-10">
                        <i class="fas fa-hands-helping text-2xl"></i>
                    </div>
                    <div class="absolute -bottom-5 -left-5 bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl shadow-2xl animate-pulse z-10">
                        <i class="fas fa-home text-2xl"></i>
                    </div>
                    
                    <!-- Additional Floating Elements -->
                    <div class="absolute -top-2 left-1/4 bg-gradient-to-br from-pink-500 to-rose-500 text-white p-3 rounded-lg shadow-xl animate-float-delayed">
                        <i class="fas fa-gift text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="fill-white w-full h-16 md:h-24">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
        </svg>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50/30 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse animation-delay-4000"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16 animate-fade-in">
            <div class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full text-sm font-semibold mb-4">
                ✨ Fitur Unggulan
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4">Mengapa Memilih Bantuin Yuk?</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Platform kami dirancang untuk memudahkan Anda dalam memberikan dan menerima bantuan dengan cara yang aman dan terpercaya.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 border border-white/50 animate-slide-up">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-hand-holding-heart text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">Mudah & Cepat</h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Hanya dengan beberapa klik, Anda dapat memposting permintaan bantuan atau menawarkan bantuan kepada yang membutuhkan.
                </p>
                <div class="mt-6 text-center">
                    <span class="inline-block w-12 h-1 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></span>
                </div>
            </div>
            
            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 border border-white/50 animate-slide-up animation-delay-200">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-shield-alt text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">Aman & Terpercaya</h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Sistem verifikasi dan rating memastikan setiap interaksi terjadi dalam lingkungan yang aman dan terpercaya.
                </p>
                <div class="mt-6 text-center">
                    <span class="inline-block w-12 h-1 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></span>
                </div>
            </div>
            
            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 border border-white/50 animate-slide-up animation-delay-400">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-tags text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">Kategori Lengkap</h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Dari bantuan kesehatan, pendidikan, hingga kebutuhan sehari-hari. Temukan bantuan yang tepat sesuai kebutuhan.
                </p>
                <div class="mt-6 text-center">
                    <span class="inline-block w-12 h-1 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full"></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-green-500 via-blue-500 to-purple-600 text-white text-center relative overflow-hidden">
    <!-- Background Animation -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1/2 bg-white/10"></div>
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-white/10 rounded-full"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto">
            <div class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-6 py-2 text-sm font-semibold mb-6">
                🚀 Bergabung Sekarang
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Siap Membantu Sesama?</h2>
            <p class="text-lg md:text-xl mb-10 opacity-90 leading-relaxed">
                Bergabunglah dengan komunitas kami sekarang dan jadilah bagian dari perubahan positif. Bersama kita bisa membuat perbedaan.
            </p>
            <div class="flex flex-wrap justify-center gap-5">
                <?php if (!isLoggedIn()): ?>
                    <a href="../auth/register.php" 
                       class="group bg-white text-gray-800 hover:bg-gray-100 font-bold py-4 px-10 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-3xl flex items-center gap-3">
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-user-plus group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="../pages/about.php" 
                       class="group bg-transparent border-2 border-white hover:bg-white hover:text-green-600 font-bold py-4 px-10 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-3">
                        <span>Pelajari Lebih Lanjut</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo isAdmin() ? '../admin/dashboard.php' : '../user/dashboard.php'; ?>" 
                       class="group bg-white text-gray-800 hover:bg-gray-100 font-bold py-4 px-10 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-3xl flex items-center gap-3">
                        <span>Lihat Dashboard</span>
                        <i class="fas fa-tachometer-alt group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="../request/create.php" 
                       class="group bg-transparent border-2 border-white hover:bg-white hover:text-green-600 font-bold py-4 px-10 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-3">
                        <span>Buat Permintaan Bantuan</span>
                        <i class="fas fa-plus group-hover:translate-x-1 transition-transform"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                    <div class="text-3xl mb-3">🤝</div>
                    <h4 class="font-bold text-lg mb-2">Komunitas Solid</h4>
                    <p class="text-white/80 text-sm">Bergabung dengan ribuan orang peduli lainnya</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                    <div class="text-3xl mb-3">⚡</div>
                    <h4 class="font-bold text-lg mb-2">Respons Cepat</h4>
                    <p class="text-white/80 text-sm">Bantuan sampai kepada yang membutuhkan dengan cepat</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                    <div class="text-3xl mb-3">🔒</div>
                    <h4 class="font-bold text-lg mb-2">Transparan</h4>
                    <p class="text-white/80 text-sm">Setiap bantuan dapat dilacak dan diverifikasi</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
@keyframes float-delayed {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.animate-float-delayed {
    animation: float-delayed 3s ease-in-out infinite;
    animation-delay: 1s;
}
.animation-delay-200 {
    animation-delay: 200ms;
}
.animation-delay-400 {
    animation-delay: 400ms;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
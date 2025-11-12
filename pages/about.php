<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Tentang Kami - Bantuin Yuk";
include '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 animate-fade-in">Tentang Bantuin Yuk</h1>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Platform gotong royong digital yang menghubungkan mereka yang membutuhkan bantuan 
                dengan mereka yang ingin membantu sesama.
            </p>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Vision & Mission -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16 animate-slide-up">
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-eye text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-center mb-6 text-gray-900">Visi Kami</h2>
                <p class="text-lg text-gray-700 text-center leading-relaxed">
                    Menjadi platform terdepan dalam membangun komunitas yang saling membantu 
                    dan peduli sesama, menciptakan dampak sosial positif melalui teknologi.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-bullseye text-green-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-center mb-6 text-gray-900">Misi Kami</h2>
                <ul class="text-lg text-gray-700 space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        <span>Memudahkan akses bantuan bagi yang membutuhkan</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        <span>Mendorong budaya gotong royong digital</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        <span>Membangun komunitas yang aman dan terpercaya</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        <span>Memberikan pengalaman pengguna yang terbaik</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Values -->
        <div class="mb-16 animate-slide-up" style="animation-delay: 0.1s">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Nilai-nilai Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-heart text-purple-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Kepedulian</h3>
                    <p class="text-gray-600">
                        Kami percaya bahwa kepedulian adalah fondasi dari setiap bantuan yang tulus dan ikhlas.
                    </p>
                </div>

                <div class="text-center bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-shield-alt text-yellow-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Keamanan</h3>
                    <p class="text-gray-600">
                        Menjaga keamanan dan privasi pengguna adalah prioritas utama dalam setiap interaksi.
                    </p>
                </div>

                <div class="text-center bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-hands-helping text-red-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Kolaborasi</h3>
                    <p class="text-gray-600">
                        Bersama-sama kita bisa menciptakan perubahan yang lebih besar dan berkelanjutan.
                    </p>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="mb-16 animate-slide-up" style="animation-delay: 0.2s">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Bagaimana Bantuin Yuk Bekerja?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold mb-4 mx-auto">1</div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-900">Buat Permintaan</h3>
                    <p class="text-gray-600 text-sm">
                        Jelaskan bantuan yang Anda butuhkan dengan jelas dan pilih kategori yang sesuai.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white text-xl font-bold mb-4 mx-auto">2</div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-900">Tunggu Tanggapan</h3>
                    <p class="text-gray-600 text-sm">
                        Relawan akan melihat permintaan Anda dan menawarkan bantuan yang sesuai.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white text-xl font-bold mb-4 mx-auto">3</div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-900">Terima Bantuan</h3>
                    <p class="text-gray-600 text-sm">
                        Pilih relawan yang tepat dan terima bantuan yang mereka tawarkan dengan aman.
                    </p>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="mb-16 animate-slide-up" style="animation-delay: 0.3s">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Tim Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="w-32 h-32 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                        AK
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900">Ahmad Kurniawan</h3>
                    <p class="text-blue-600 mb-2">Founder & CEO</p>
                    <p class="text-gray-600 text-sm">
                        Penggagas ide Bantuin Yuk dengan visi membangun platform gotong royong digital.
                    </p>
                </div>

                <div class="text-center bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="w-32 h-32 bg-gradient-to-r from-green-400 to-blue-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                        SD
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900">Sari Dewi</h3>
                    <p class="text-blue-600 mb-2">CTO</p>
                    <p class="text-gray-600 text-sm">
                        Ahli teknologi dengan pengalaman 10+ tahun dalam pengembangan platform digital.
                    </p>
                </div>

                <div class="text-center bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="w-32 h-32 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                        RM
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900">Rizky Maulana</h3>
                    <p class="text-blue-600 mb-2">Head of Community</p>
                    <p class="text-gray-600 text-sm">
                        Membangun dan mengelola komunitas dengan fokus pada engagement dan trust.
                    </p>
                </div>

                <div class="text-center bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="w-32 h-32 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                        LS
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900">Lisa Sari</h3>
                    <p class="text-blue-600 mb-2">Product Manager</p>
                    <p class="text-gray-600 text-sm">
                        Memastikan produk sesuai dengan kebutuhan pengguna dan memberikan nilai terbaik.
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-8 text-white text-center animate-slide-up" style="animation-delay: 0.4s">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <div class="text-3xl font-bold mb-2">500+</div>
                    <div class="text-blue-100">Pengguna Terdaftar</div>
                </div>
                <div>
                    <div class="text-3xl font-bold mb-2">1.2K+</div>
                    <div class="text-blue-100">Permintaan Bantuan</div>
                </div>
                <div>
                    <div class="text-3xl font-bold mb-2">15+</div>
                    <div class="text-blue-100">Kota Terjangkau</div>
                </div>
                <div>
                    <div class="text-3xl font-bold mb-2">98%</div>
                    <div class="text-blue-100">Kepuasan Pengguna</div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center mt-16 animate-fade-in">
            <h2 class="text-3xl font-bold mb-6 text-gray-900">Siap Bergabung dengan Komunitas Kami?</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Jadilah bagian dari perubahan positif dan mulai bantu sesama hari ini.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <?php if (!isLoggedIn()): ?>
                    <a href="../auth/register.php" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg">
                        Daftar Sekarang
                    </a>
                    <a href="../auth/login.php" 
                       class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200">
                        Masuk
                    </a>
                <?php else: ?>
                    <a href="../request/create.php" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg">
                        Buat Permintaan Bantuan
                    </a>
                    <a href="../request/list.php" 
                       class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200">
                        Lihat Permintaan Lain
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
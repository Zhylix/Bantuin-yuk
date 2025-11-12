    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <span class="text-xl font-bold">Bantuin Yuk</span>
                    </div>
                    <p class="text-gray-300 mb-4 max-w-md">
                        Platform untuk menghubungkan mereka yang membutuhkan bantuan dengan mereka yang ingin membantu. 
                        Mari wujudkan kepedulian sosial dalam genggaman Anda.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="<?php echo BASE_URL; ?>/pages/home.php" class="text-gray-300 hover:text-white transition-colors duration-200">Beranda</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/request/list.php" class="text-gray-300 hover:text-white transition-colors duration-200">Daftar Permintaan</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/map.php" class="text-gray-300 hover:text-white transition-colors duration-200">Peta Bantuan</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/about.php" class="text-gray-300 hover:text-white transition-colors duration-200">Tentang Kami</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>hello@bantuinyuk.com</span>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-phone mr-2"></i>
                            <span>+62 21 1234 5678</span>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-300 text-sm">
                    &copy; <?php echo date('Y'); ?> Bantuin Yuk. All rights reserved.
                </p>
                <div class="mt-4 md:mt-0 flex space-x-6 text-sm">
                    <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Kebijakan Privasi</a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/animations.js"></script>
    
    <script>
        // Hide loading spinner when page is loaded
        window.addEventListener('load', function() {
            const loading = document.getElementById('loading');
            if (loading) {
                loading.style.opacity = '0';
                setTimeout(() => {
                    loading.style.display = 'none';
                }, 300);
            }
        });

        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (mobileMenu && !mobileMenu.contains(e.target) && mobileMenuButton && !mobileMenuButton.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
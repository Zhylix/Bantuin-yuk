<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('/auth/login.php');
}

$tags = getAllTags();
$page_title = "Buat Permintaan Bantuan - Bantuin Yuk";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden animate-fade-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Buat Permintaan Bantuan Baru</h1>
                        <p class="text-blue-100 mt-1">Bagikan kebutuhan bantuan Anda kepada komunitas</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="../process/request_process.php" method="POST" class="p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>
                        Judul Permintaan
                    </label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="Contoh: Butuh donor darah untuk operasi jantung"
                           maxlength="200">
                    <p class="text-xs text-gray-500 mt-1">Buat judul yang jelas dan deskriptif</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-green-500"></i>
                        Deskripsi Lengkap
                    </label>
                    <textarea id="description" name="description" rows="6" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 resize-none"
                              placeholder="Jelaskan secara detail bantuan yang Anda butuhkan, termasuk alasan dan situasi saat ini..."
                              data-autoresize></textarea>
                    <p class="text-xs text-gray-500 mt-1">Semakin detail, semakin mudah orang lain memahami kebutuhan Anda</p>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                        Lokasi
                    </label>
                    <input type="text" id="location" name="location" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                           placeholder="Contoh: Jakarta Selatan, DKI Jakarta">
                    <p class="text-xs text-gray-500 mt-1">Sebutkan kota/kecamatan untuk memudahkan relawan terdekat</p>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags mr-2 text-purple-500"></i>
                        Kategori Bantuan
                    </label>
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-3">Pilih kategori yang sesuai dengan kebutuhan bantuan Anda:</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <?php foreach($tags as $tag): ?>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-white hover:border-purple-300 transition-all duration-200 tag-option"
                                           data-tag-id="<?php echo $tag['id']; ?>"
                                           data-tag-name="<?php echo htmlspecialchars($tag['name']); ?>">
                                        <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" 
                                               class="hidden tag-checkbox">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded mr-3 flex items-center justify-center transition-colors duration-200">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?php echo $tag['color']; ?>"></div>
                                            <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($tag['name']); ?></span>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Selected Tags Display -->
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Kategori yang dipilih:</p>
                            <div class="tags-container flex flex-wrap gap-2 min-h-10 p-2 bg-white rounded border border-dashed border-gray-300">
                                <p class="text-gray-400 text-sm">Belum ada kategori dipilih</p>
                            </div>
                            <input type="hidden" name="selected_tags" id="selected_tags">
                        </div>
                    </div>
                </div>

                <!-- Urgency -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2 text-orange-500"></i>
                        Tingkat Urgensi
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="urgency-option relative">
                            <input type="radio" name="urgency" value="low" class="hidden" checked>
                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-clock text-green-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Rendah</span>
                                <p class="text-xs text-gray-500 mt-1">Bisa menunggu</p>
                            </div>
                        </label>
                        
                        <label class="urgency-option relative">
                            <input type="radio" name="urgency" value="medium" class="hidden">
                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-exclamation text-blue-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Sedang</span>
                                <p class="text-xs text-gray-500 mt-1">Butuh segera</p>
                            </div>
                        </label>
                        
                        <label class="urgency-option relative">
                            <input type="radio" name="urgency" value="high" class="hidden">
                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition-all duration-200">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-exclamation-circle text-orange-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Tinggi</span>
                                <p class="text-xs text-gray-500 mt-1">Sangat penting</p>
                            </div>
                        </label>
                        
                        <label class="urgency-option relative">
                            <input type="radio" name="urgency" value="critical" class="hidden">
                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all duration-200">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-skull-crossbones text-red-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Kritis</span>
                                <p class="text-xs text-gray-500 mt-1">Darurat</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                        Batas Waktu (Opsional)
                    </label>
                    <input type="date" id="deadline" name="deadline"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                           min="<?php echo date('Y-m-d'); ?>">
                    <p class="text-xs text-gray-500 mt-1">Tentukan batas waktu jika bantuan memiliki tenggat waktu</p>
                </div>

                <!-- Help Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-hand-holding-heart mr-2 text-pink-500"></i>
                        Jenis Bantuan
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="help-type-option relative">
                            <input type="radio" name="help_type" value="request" class="hidden" checked>
                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-pink-300 hover:bg-pink-50 transition-all duration-200">
                                <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-hands-helping text-pink-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Minta Bantuan</span>
                                <p class="text-xs text-gray-500 mt-1">Saya butuh bantuan</p>
                            </div>
                        </label>
                        
                        <label class="help-type-option relative">
                            <input type="radio" name="help_type" value="offer" class="hidden">
                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-teal-300 hover:bg-teal-50 transition-all duration-200">
                                <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-heart text-teal-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Tawarkan Bantuan</span>
                                <p class="text-xs text-gray-500 mt-1">Saya ingin membantu</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-purple-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 shadow-md">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Publikasikan Permintaan
                    </button>
                    <a href="../user/dashboard.php" 
                       class="flex-1 bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold text-center hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batalkan
                    </a>
                </div>
            </form>
        </div>

        <!-- Tips Section -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6 border border-blue-200 animate-slide-up">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-lightbulb text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Tips Membuat Permintaan yang Efektif</h3>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Gunakan judul yang jelas dan spesifik</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Jelaskan situasi Anda dengan detail namun tetap ringkas</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Pilih kategori yang tepat agar mudah ditemukan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Tentukan tingkat urgensi yang sesuai</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Sebutkan lokasi yang jelas untuk memudahkan relawan terdekat</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tag selection functionality
    const tagOptions = document.querySelectorAll('.tag-option');
    const tagsContainer = document.querySelector('.tags-container');
    const selectedTagsInput = document.getElementById('selected_tags');
    let selectedTags = [];

    tagOptions.forEach(option => {
        option.addEventListener('click', function() {
            const tagId = this.dataset.tagId;
            const tagName = this.dataset.tagName;
            const checkbox = this.querySelector('.tag-checkbox');
            
            if (checkbox.checked) {
                // Remove tag
                checkbox.checked = false;
                this.classList.remove('bg-purple-50', 'border-purple-300');
                this.querySelector('.w-5').classList.remove('bg-purple-600', 'border-purple-600');
                selectedTags = selectedTags.filter(id => id !== tagId);
            } else {
                // Add tag
                checkbox.checked = true;
                this.classList.add('bg-purple-50', 'border-purple-300');
                this.querySelector('.w-5').classList.add('bg-purple-600', 'border-purple-600');
                selectedTags.push(tagId);
            }
            
            updateTagsDisplay();
        });
    });

    function updateTagsDisplay() {
        tagsContainer.innerHTML = '';
        
        if (selectedTags.length === 0) {
            tagsContainer.innerHTML = '<p class="text-gray-400 text-sm">Belum ada kategori dipilih</p>';
        } else {
            selectedTags.forEach(tagId => {
                const tagOption = document.querySelector(`.tag-option[data-tag-id="${tagId}"]`);
                if (tagOption) {
                    const tagName = tagOption.dataset.tagName;
                    const tagElement = document.createElement('span');
                    tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800';
                    tagElement.innerHTML = `
                        ${tagName}
                        <button type="button" class="ml-1 text-purple-600 hover:text-purple-800 remove-tag" data-tag-id="${tagId}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    tagsContainer.appendChild(tagElement);
                }
            });
        }
        
        selectedTagsInput.value = selectedTags.join(',');
    }

    // Remove tag when X is clicked
    tagsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-tag')) {
            const tagId = e.target.closest('.remove-tag').dataset.tagId;
            const tagOption = document.querySelector(`.tag-option[data-tag-id="${tagId}"]`);
            
            if (tagOption) {
                tagOption.click(); // Trigger click to toggle selection
            }
        }
    });

    // Urgency option styling
    const urgencyOptions = document.querySelectorAll('.urgency-option');
    urgencyOptions.forEach(option => {
        option.addEventListener('click', function() {
            urgencyOptions.forEach(opt => {
                opt.querySelector('div').classList.remove('border-blue-500', 'bg-blue-50');
            });
            this.querySelector('div').classList.add('border-blue-500', 'bg-blue-50');
        });
    });

    // Help type option styling
    const helpTypeOptions = document.querySelectorAll('.help-type-option');
    helpTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            helpTypeOptions.forEach(opt => {
                opt.querySelector('div').classList.remove('border-blue-500', 'bg-blue-50');
            });
            this.querySelector('div').classList.add('border-blue-500', 'bg-blue-50');
        });
    });

    // Auto-resize textarea
    const textarea = document.getElementById('description');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
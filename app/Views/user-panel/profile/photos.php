<?= $this->include('user-panel/includes/header') ?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= localized_url('user-panel/profile/edit-photos') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Upload New Photos</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-violet-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="bi bi-cloud-arrow-up text-4xl text-slate-400"></i>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="imageUpload" class="relative cursor-pointer bg-white rounded-md font-medium text-violet-600 hover:text-violet-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-violet-500">
                                    <span>Upload files</span>
                                    <input id="imageUpload" name="photos[]" type="file" class="sr-only" multiple accept="image/jpeg, image/png, image/webp">
                                </label>
                            </div>
                            <p class="text-xs text-slate-500">PNG, JPG, WEBP up to 5MB each</p>
                        </div>
                    </div>
                </div>

                <div id="previewContainer" class="flex flex-wrap gap-4 mb-6 empty:hidden"></div>

                <?php 
                $images = [];
                if (!empty($profile['images'])) {
                    $images = is_string($profile['images']) ? json_decode($profile['images'], true) : $profile['images'];
                }
                
                if (!empty($images) && is_array($images)): 
                ?>
                    <h3 class="text-sm font-semibold text-slate-700 mb-4 border-b pb-2">Current Gallery</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-6">
                        <?php foreach ($images as $img): ?>
                            <div class="relative group aspect-[3/4]">
                                <img src="<?= rtrim((string) env('app.cdnURL'), '/') . '/images/users/' . esc($img) ?>" 
                                     class="w-full h-full object-cover rounded-xl shadow-sm border border-slate-200">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                    <button type="button" 
                                            class="bg-red-500 hover:bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition-transform transform scale-90 group-hover:scale-100 delete-image-btn"
                                            data-filename="<?= esc($img) ?>"
                                            title="Delete Photo">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white py-4 rounded-3xl font-semibold transition flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <i class="bi bi-cloud-upload"></i> Upload & Save
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const imageUpload = document.getElementById('imageUpload');
    const previewContainer = document.getElementById('previewContainer');
    let csrfToken = '<?= csrf_hash() ?>';
    const csrfName = '<?= csrf_token() ?>';

    if (imageUpload) {
        imageUpload.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            
            if (this.files.length > 0) {
                previewContainer.innerHTML = '<div class="w-full text-sm font-semibold text-slate-700 mb-2">Ready to upload:</div>';
            }

            Array.from(this.files).forEach(file => {
                const reader = new FileReader();

                reader.onload = e => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative aspect-[3/4] w-24 sm:w-32 flex-shrink-0';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover rounded-xl shadow-sm border-2 border-violet-200';

                    wrapper.appendChild(img);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            });
        });
    }

    document.querySelectorAll('.delete-image-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filename = this.dataset.filename;

            if (!confirm('Are you sure you want to delete this photo from your gallery?')) return;

            const formData = new URLSearchParams();
            formData.append('filename', filename);
            formData.append(csrfName, csrfToken);

            fetch('<?= localized_url('user-panel/profile/delete-photo') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.csrfHash) {
                    csrfToken = data.csrfHash;
                }

                if (data.success) {
                    this.closest('.relative').remove();
                } else {
                    alert(data.error || 'Failed to delete image.');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('A network error occurred while trying to delete the photo.');
            });
        });
    });

});
</script>

<?= $this->include('user-panel/includes/footer') ?>

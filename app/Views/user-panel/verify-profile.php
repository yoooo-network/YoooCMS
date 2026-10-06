<?= $this->include('user-panel/includes/header') ?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-3xl mx-auto">
        
        <!-- Page Header -->
        <div class="mb-6 text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-violet-100 text-violet-600 mb-4 shadow-sm">
                <i class="bi bi-patch-check-fill text-3xl"></i>
            </div>
            <h2 class="font-bold text-2xl text-slate-800">Verify Your Profile</h2>
            <p class="text-sm text-slate-500 mt-2">To ensure the safety of our community, we require all users to verify their identity.</p>
        </div>

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

            <form action="<?= localized_url('user-panel/apply-verification') ?>" method="post" enctype="multipart/form-data" id="verifyForm">
                <?= csrf_field() ?>

                <?php $uploadedFiles = is_array($verificationFiles ?? null) ? $verificationFiles : []; ?>

                <div class="space-y-6">
                    <!-- Live Selfie -->
                    <div class="p-5 border-2 border-dashed border-slate-200 rounded-2xl hover:border-violet-300 transition-colors bg-slate-50/50">
                        <label for="liveSelfieFile" class="block font-semibold text-slate-800 mb-2">Upload Live Selfie</label>
                        <p class="text-xs text-slate-500 mb-4">Please upload a clear picture of your face right now.</p>
                        
                        <input
                            type="file"
                            id="liveSelfieFile"
                            name="live_selfie_file"
                            accept="image/jpeg,image/jpg,image/png,image/webp"
                            data-has-upload="<?= !empty($uploadedFiles['live_selfie']) ? '1' : '0' ?>"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                        
                        <p class="text-xs text-slate-400 mt-3">Allowed: JPG, PNG, WEBP (max 5MB).</p>
                        <?php if (!empty($uploadedFiles['live_selfie'])): ?>
                            <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium border border-green-100">
                                <i class="bi bi-check-circle-fill mr-1.5"></i> Uploaded: <?= esc(basename($uploadedFiles['live_selfie'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- ID Document -->
                    <div class="p-5 border-2 border-dashed border-slate-200 rounded-2xl hover:border-violet-300 transition-colors bg-slate-50/50">
                        <label for="idDocumentFile" class="block font-semibold text-slate-800 mb-2">Upload ID Document</label>
                        <p class="text-xs text-slate-500 mb-4">Upload a clear front-side photo of a valid government-issued ID.</p>
                        
                        <input
                            type="file"
                            id="idDocumentFile"
                            name="id_document_file"
                            accept="image/jpeg,image/jpg,image/png,image/webp"
                            data-has-upload="<?= !empty($uploadedFiles['id_verification']) ? '1' : '0' ?>"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                        
                        <p class="text-xs text-slate-400 mt-3">Allowed: JPG, PNG, WEBP (max 5MB).</p>
                        <?php if (!empty($uploadedFiles['id_verification'])): ?>
                            <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium border border-green-100">
                                <i class="bi bi-check-circle-fill mr-1.5"></i> Uploaded: <?= esc(basename($uploadedFiles['id_verification'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="verifyError" class="hidden mt-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-100 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> <span id="verifyErrorText">Please upload both required files.</span>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 text-center">
                    <button type="submit" class="w-full sm:w-auto min-w-[200px] bg-violet-600 hover:bg-violet-700 text-white py-3.5 px-8 rounded-full font-semibold transition shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2">
                        <i class="bi bi-shield-lock-fill"></i> Submit for Verification
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('verifyForm');
  const errorAlert = document.getElementById('verifyError');
  const errorText = document.getElementById('verifyErrorText');
  const liveSelfieFile = document.getElementById('liveSelfieFile');
  const idDocumentFile = document.getElementById('idDocumentFile');

  if (!form) return;

  form.addEventListener('submit', (event) => {
    const hasSelfieUpload = liveSelfieFile && (liveSelfieFile.value || liveSelfieFile.dataset.hasUpload === '1');
    if (!hasSelfieUpload) {
      event.preventDefault();
      errorText.textContent = 'Please upload your live selfie photo.';
      errorAlert.classList.remove('hidden');
      return;
    }

    const hasIdUpload = idDocumentFile && (idDocumentFile.value || idDocumentFile.dataset.hasUpload === '1');
    if (!hasIdUpload) {
      event.preventDefault();
      errorText.textContent = 'Please upload your ID document photo.';
      errorAlert.classList.remove('hidden');
      return;
    }

    errorAlert.classList.add('hidden');
  });
});
</script>

<?= $this->include('user-panel/includes/footer') ?>

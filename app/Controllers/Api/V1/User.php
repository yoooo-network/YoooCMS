<?php

namespace App\Controllers\Api\V1;

use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Services\EmailService;
use Throwable;

class User extends BaseController
{
    private function getVerificationUploadPath(int $userId): string
    {
        return WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'verifications' . DIRECTORY_SEPARATOR . 'user_' . $userId . DIRECTORY_SEPARATOR;
    }

    private function storeVerificationFile(int $userId, \CodeIgniter\HTTP\Files\UploadedFile $file, string $prefix): ?string
    {
        if (!$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $maxFileSize = 5 * 1024 * 1024;
        if ($file->getSize() > $maxFileSize) {
            return null;
        }

        $mimeType = strtolower((string) $file->getMimeType());
        $allowedMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($mimeType, $allowedMime, true)) {
            return null;
        }

        $uploadPath = $this->getVerificationUploadPath($userId);
        if (!is_dir($uploadPath) && !@mkdir($uploadPath, 0775, true) && !is_dir($uploadPath)) {
            return null;
        }

        $extension = strtolower((string) $file->getClientExtension());
        if ($extension === '') {
            $extension = match ($mimeType) {
                'image/jpeg', 'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg',
            };
        }

        $fileName = $prefix . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        if (!$file->move($uploadPath, $fileName)) {
            return null;
        }

        return $fileName;
    }

    private function buildWebpVariants(string $originalPath, string $baseOutputPath): bool
    {
        $mainWebp = $baseOutputPath . '.webp';
        $webp400 = $baseOutputPath . '_400.webp';
        $webp800 = $baseOutputPath . '_800.webp';

        if (extension_loaded('imagick')) {
            $imgMain = new \Imagick($originalPath);
            $imgMain->stripImage();
            $imgMain->setImageFormat('webp');
            $imgMain->setImageCompressionQuality(85);
            $imgMain->writeImage($mainWebp);

            $w = $imgMain->getImageWidth();
            $h = $imgMain->getImageHeight();
            $size = min($w, $h);
            $x = (int) (($w - $size) / 2);
            $y = (int) (($h - $size) / 2);

            $square = clone $imgMain;
            $square->cropImage($size, $size, $x, $y);
            $square->setImagePage(0, 0, 0, 0);

            $thumb400 = clone $square;
            $thumb400->resizeImage(400, 400, \Imagick::FILTER_LANCZOS, 1);
            $thumb400->setImageCompressionQuality(80);
            $thumb400->writeImage($webp400);
            $thumb400->clear();

            $thumb800 = clone $square;
            $thumb800->resizeImage(800, 800, \Imagick::FILTER_LANCZOS, 1);
            $thumb800->setImageCompressionQuality(80);
            $thumb800->writeImage($webp800);
            $thumb800->clear();

            $square->clear();
            $imgMain->clear();
            return true;
        }

        $mime = mime_content_type($originalPath);
        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $src = imagecreatefromjpeg($originalPath);
        } elseif ($mime === 'image/png') {
            $src = imagecreatefrompng($originalPath);
            imagepalettetotruecolor($src);
            imagealphablending($src, true);
            imagesavealpha($src, true);
        } elseif ($mime === 'image/webp') {
            $src = imagecreatefromwebp($originalPath);
        } else {
            return false;
        }

        if (!$src) {
            return false;
        }

        imagewebp($src, $mainWebp, 85);

        $w = imagesx($src);
        $h = imagesy($src);
        $size = min($w, $h);
        $x = (int) (($w - $size) / 2);
        $y = (int) (($h - $size) / 2);

        $square = imagecreatetruecolor($size, $size);
        imagecopyresampled($square, $src, 0, 0, $x, $y, $size, $size, $size, $size);

        $t400 = imagecreatetruecolor(400, 400);
        imagecopyresampled($t400, $square, 0, 0, 0, 0, 400, 400, $size, $size);
        imagewebp($t400, $webp400, 80);

        $t800 = imagecreatetruecolor(800, 800);
        imagecopyresampled($t800, $square, 0, 0, 0, 0, 800, 800, $size, $size);
        imagewebp($t800, $webp800, 80);

        imagedestroy($t400);
        imagedestroy($t800);
        imagedestroy($square);
        imagedestroy($src);

        return true;
    }

    private function deleteProfileImages(?array $profile): void
    {
        if (!is_array($profile) || empty($profile['images'])) {
            return;
        }

        $images = json_decode((string) $profile['images'], true) ?? [];
        if (!is_array($images)) {
            return;
        }

        $baseDir = FCPATH . 'images/users/';
        foreach ($images as $filename) {
            $baseName = pathinfo((string) $filename, PATHINFO_FILENAME);
            foreach ([$baseName . '.webp', $baseName . '_400.webp', $baseName . '_800.webp'] as $file) {
                $fullPath = $baseDir . $file;
                if (is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }
        }
    }

    private function isProfileComplete(array $profile): bool
    {
        $requiredFields = [
            'name', 'gender', 'dob', 'location', 'height', 'weight', 'description',
            'images', 'languages', 'pricing', 'services', 'phone'
        ];

        $jsonFields = ['images', 'pricing', 'services', 'languages', 'other_pages', 'sexuality'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $profile)) {
                return false;
            }

            $value = $profile[$field];
            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === '' || $value === null) {
                return false;
            }

            if (in_array($field, $jsonFields, true)) {
                if (is_string($profile[$field])) {
                    $decoded = json_decode($profile[$field], true);
                    if (!is_array($decoded) || empty($decoded)) {
                        return false;
                    }
                } elseif (is_array($profile[$field]) && empty($profile[$field])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the authenticated user's dashboard data
     */
    public function dashboard()
    {
        try {
            $userId = $this->request->api_user_id ?? null;

            if (empty($userId)) {
                return $this->sendError('Unauthorized', 401);
            }

            $userModel = new UserModel();
            $profileModel = new ProfileModel();

            $user = $userModel->find($userId);
            if (!$user) {
                return $this->sendError('User not found', 404);
            }

            $profile = $profileModel->where('user_id', $userId)->first();

            // Calculate profile completion percentage
            $completion = 0;
            if ($profile) {
                $fields = ['name', 'gender', 'dob', 'location', 'description', 'images', 'phone'];
                $filled = 0;
                foreach ($fields as $field) {
                    if (array_key_exists($field, $profile) && !empty($profile[$field])) {
                        $filled++;
                    }
                }
                $completion = round(($filled / count($fields)) * 100);
            }

            $profileStatus = 'pending';
            $profileIsVerified = false;
            $profileMembership = 'free';

            if ($profile) {
                $profileStatus = (string) ($profile['status'] ?? $profileStatus);
                $profileIsVerified = !empty($profile['is_verified']);
                $profileMembership = (string) ($profile['membership'] ?? $profileMembership);
            }

            return $this->sendResponse([
                'user' => [
                    'id' => (int)$user['id'],
                    'name' => $user['name'] ?? '',
                    'email' => $user['email'],
                ],
                'profile' => $profile ? [
                    'id' => (int)$profile['id'],
                    'status' => $profileStatus,
                    'is_verified' => $profileIsVerified,
                    'membership' => $profileMembership,
                    'completion' => $completion,
                ] : null
            ]);
        } catch (Throwable $e) {
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get the full profile data for editing
     */
    public function profile()
    {
        try {
            $userId = $this->request->api_user_id;
            $profileModel = new ProfileModel();

            $profile = $profileModel->where('user_id', $userId)->first();

            if (!$profile) {
                return $this->sendResponse(['profile' => null]);
            }

            // Parse JSON fields if they are stored as strings
            $jsonFields = ['images', 'pricing', 'services', 'languages', 'other_pages', 'sexuality'];
            foreach ($jsonFields as $field) {
                if (isset($profile[$field]) && is_string($profile[$field])) {
                    $decoded = json_decode($profile[$field], true);
                    $profile[$field] = $decoded ?: [];
                }
            }

            return $this->sendResponse(['profile' => $profile]);
        } catch (Throwable $e) {
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update or create user profile
     */
    public function updateProfile()
    {
        try {
            $userId = $this->request->api_user_id;
            $profileModel = new ProfileModel();

            $input = $this->request->getJSON(true);
            if (!$input) {
                return $this->sendError('Invalid JSON input', 400);
            }

            // Step-wise profile flow sends partial payloads.
            // Validate only fields that are present and non-empty in this request.
            $rules = [];
            if (array_key_exists('name', $input) && trim((string) $input['name']) !== '') {
                $rules['name'] = 'min_length[3]|max_length[100]';
            }
            if (array_key_exists('gender', $input) && trim((string) $input['gender']) !== '') {
                $rules['gender'] = 'in_list[male,female,other,trans]';
            }
            if (array_key_exists('dob', $input) && trim((string) $input['dob']) !== '') {
                $rules['dob'] = 'valid_date';
            }
            if (array_key_exists('location', $input) && trim((string) $input['location']) !== '') {
                $rules['location'] = 'min_length[3]|max_length[255]';
            }

            if ($rules !== [] && !$this->validateData($input, $rules)) {
                $errors = $this->validator->getErrors();
                return $this->sendError('Validation Failed', 422, $errors);
            }

            $existingProfile = $profileModel->where('user_id', $userId)->first();

            $fields = [
                'name', 'gender', 'dob', 'location', 'description', 'phone',
                'whatsapp', 'telegram', 'facebook', 'instagram', 'discord', 'website',
                'height', 'weight', 'eye_color', 'hair_type', 'skin_color',
                'body_structure', 'ethnicity',
            ];

            $data = ['user_id' => $userId];
            foreach ($fields as $field) {
                if (array_key_exists($field, $input)) {
                    $data[$field] = $input[$field] !== '' ? $input[$field] : null;
                }
            }

            // Handle JSON fields
            $jsonFields = ['images', 'pricing', 'services', 'languages', 'other_pages', 'sexuality'];
            foreach ($jsonFields as $field) {
                if (array_key_exists($field, $input)) {
                    $data[$field] = json_encode($input[$field]);
                }
            }

            $mergedProfile = $existingProfile ? array_merge($existingProfile, $data) : $data;
            $isComplete = $this->isProfileComplete($mergedProfile);

            if ($existingProfile) {
                $previousStatus = strtolower(trim((string) ($existingProfile['status'] ?? '')));
                $shouldSendActiveEmail = false;

                if ($isComplete) {
                    $data['status'] = 'approved';
                    $shouldSendActiveEmail = $previousStatus !== 'approved';
                }

                $profileModel->update($existingProfile['id'], $data);

                if ($shouldSendActiveEmail) {
                    $savedProfile = $profileModel->find($existingProfile['id']);
                    if (is_array($savedProfile) && !empty($savedProfile['user_id'])) {
                        $user = (new UserModel())->find((int) $savedProfile['user_id']);
                        if (is_array($user) && !empty($user['email'])) {
                            helper('text');
                            $profileName = trim((string) ($savedProfile['name'] ?? $user['name'] ?? ''));
                            $slug = $profileName !== '' ? url_title($profileName, '-', true) : 'profile-' . (string) $savedProfile['id'];
                            $language = 'en';
                            $profileUrl = site_url($language . '/profile/' . $savedProfile['id'] . '/' . $slug);

                            $profileGender = strtolower(trim((string) ($savedProfile['gender'] ?? default_site_category())));
                            $sexualities = json_decode((string) ($savedProfile['sexuality'] ?? '[]'), true);
                            if (is_array($sexualities) && isset($sexualities['selected']) && is_array($sexualities['selected'])) {
                                $sexualities = $sexualities['selected'];
                            }
                            $sexualities = is_array($sexualities) ? array_map(static fn($value) => strtolower(trim((string) $value)), $sexualities) : [];
                            $categorySlug = $profileGender === 'male'
                                ? (array_intersect(['homo', 'bisexual'], $sexualities) ? 'gay' : 'male')
                                : (in_array($profileGender, ['female', 'trans'], true) ? $profileGender : default_site_category());
                            if (! in_array($categorySlug, site_categories(), true)) $categorySlug = default_site_category();

                            $locationParts = array_values(array_filter(array_map('trim', explode(',', (string) ($savedProfile['location'] ?? '')))));
                            $city = $locationParts[0] ?? '';
                            $country = $locationParts !== [] ? (string) $locationParts[count($locationParts) - 1] : '';

                            $cityUrl = site_url($language . '/user/dashboard');
                            if ($country !== '' && $city !== '') {
                                $countrySlug = url_title($country, '-', true);
                                $citySlug = url_title($city, '-', true);
                                $cityUrl = site_url($language . '/' . $categorySlug . '/' . $countrySlug . '/' . $citySlug);
                            }

                            $emailService = new EmailService();
                            $sent = $emailService->sendProfileActiveEmail([
                                'name' => $user['name'] ?? '',
                                'email' => $user['email'] ?? '',
                            ], $profileUrl, $cityUrl);

                            if (!$sent) {
                                log_message('error', 'Profile active email failed for profile ID: ' . (string) $savedProfile['id']);
                            }
                        }
                    }
                }

                $message = 'Profile updated successfully';
            } else {
                $data['status'] = $isComplete ? 'approved' : 'pending';
                $data['membership'] = 'free';
                $data['is_verified'] = 0;
                $profileModel->insert($data);
                $message = 'Profile created successfully';
            }

            return $this->sendResponse([], $message);
        } catch (Throwable $e) {
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Upload a single profile photo and append it to user's images list.
     */
    public function uploadPhoto()
    {
        try {
            $userId = $this->request->api_user_id ?? null;
            if (empty($userId)) {
                return $this->sendError('Unauthorized', 401);
            }

            $file = $this->request->getFile('photo');
            if (!$file) {
                $file = $this->request->getFile('photos');
            }
            if (!$file) {
                $allFiles = $this->request->getFiles();
                if (is_array($allFiles)) {
                    foreach ($allFiles as $candidate) {
                        if ($candidate instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                            $file = $candidate;
                            break;
                        }
                        if (is_array($candidate)) {
                            foreach ($candidate as $nested) {
                                if ($nested instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                                    $file = $nested;
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }

            if (!$file) {
                return $this->sendError('Invalid photo upload: file field not found', 400);
            }
            if (!$file->isValid()) {
                return $this->sendError('Invalid photo upload: ' . $file->getErrorString(), 400);
            }

            $maxFileSize = 5 * 1024 * 1024;
            if ($file->getSize() > $maxFileSize) {
                return $this->sendError('Each photo must be 5MB or smaller', 413);
            }

            $mimeType = strtolower((string) $file->getMimeType());
            $allowedMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($mimeType, $allowedMime, true)) {
                return $this->sendError('Only JPG, PNG, and WEBP images are allowed', 422);
            }

            $uploadPath = FCPATH . 'images' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR;
            if (!is_dir($uploadPath) && !@mkdir($uploadPath, 0775, true) && !is_dir($uploadPath)) {
                return $this->sendError('Failed to prepare upload directory', 500);
            }

            $baseName = 'user_' . $userId . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4));
            $originalName = $baseName . '.' . $file->getClientExtension();
            $originalPath = $uploadPath . $originalName;

            if (!$file->move($uploadPath, $originalName)) {
                return $this->sendError('Upload failed', 500);
            }

            $converted = $this->buildWebpVariants($originalPath, $uploadPath . $baseName);
            @unlink($originalPath);
            if (!$converted) {
                return $this->sendError('Failed to process image', 500);
            }

            $profileModel = new ProfileModel();
            $existing = $profileModel->where('user_id', $userId)->first();
            $existingImages = [];

            if ($existing && !empty($existing['images'])) {
                $existingImages = json_decode((string) $existing['images'], true) ?? [];
            }

            $existingImages[] = $baseName . '.webp';

            if ($existing) {
                $profileModel->update($existing['id'], ['images' => json_encode($existingImages)]);
                $profile = $profileModel->where('user_id', $userId)->first();
                if ($profile && $this->isProfileComplete($profile)) {
                    $profileModel->update($existing['id'], ['status' => 'approved']);
                }
            } else {
                $profileModel->insert([
                    'user_id' => $userId,
                    'images' => json_encode($existingImages),
                    'status' => 'pending',
                    'membership' => 'free',
                    'is_verified' => 0,
                ]);
            }

            return $this->sendResponse([
                'image' => $baseName . '.webp',
                'images' => $existingImages,
            ], 'Photo uploaded successfully');
        } catch (Throwable $e) {
            log_message('error', 'Upload photo failed for user ' . (string) ($this->request->api_user_id ?? 'unknown') . ': ' . $e->getMessage());
            return $this->sendError('Server Error while uploading photo', 500);
        }
    }

    /**
     * Upload profile verification files (live selfie + ID document).
     */
    public function uploadVerificationFiles()
    {
        try {
            $userId = $this->request->api_user_id ?? null;
            if (empty($userId)) {
                return $this->sendError('Unauthorized', 401);
            }

            $liveSelfie = $this->request->getFile('live_selfie_file');
            $idDocument = $this->request->getFile('id_document_file');

            if (
                (!$liveSelfie || $liveSelfie->getError() === UPLOAD_ERR_NO_FILE) &&
                (!$idDocument || $idDocument->getError() === UPLOAD_ERR_NO_FILE)
            ) {
                return $this->sendError('Please upload at least one verification image.', 422);
            }

            $uploaded = [];

            if ($liveSelfie && $liveSelfie->isValid()) {
                $saved = $this->storeVerificationFile((int) $userId, $liveSelfie, 'live_selfie_');
                if ($saved === null) {
                    return $this->sendError('Invalid live selfie file. Allowed: JPG, PNG, WEBP up to 5MB.', 422);
                }
                $uploaded['live_selfie'] = $saved;
            }

            if ($idDocument && $idDocument->isValid()) {
                $saved = $this->storeVerificationFile((int) $userId, $idDocument, 'id_verification_');
                if ($saved === null) {
                    return $this->sendError('Invalid ID document file. Allowed: JPG, PNG, WEBP up to 5MB.', 422);
                }
                $uploaded['id_verification'] = $saved;
            }

            if ($uploaded === []) {
                return $this->sendError('No valid verification files were uploaded.', 422);
            }

            return $this->sendResponse([
                'files' => $uploaded,
            ], 'Verification files uploaded successfully.');
        } catch (Throwable $e) {
            log_message('error', 'Verification upload failed for user ' . (string) ($this->request->api_user_id ?? 'unknown') . ': ' . $e->getMessage());
            return $this->sendError('Server Error while uploading verification files', 500);
        }
    }

    /**
     * Change authenticated user's password.
     */
    public function changePassword()
    {
        try {
            $userId = $this->request->api_user_id ?? null;
            if (empty($userId)) {
                return $this->sendError('Unauthorized', 401);
            }

            $input = $this->request->getJSON(true);
            if (!is_array($input) || $input === []) {
                $input = $this->request->getPost();
            }

            $currentPassword = trim((string) ($input['current_password'] ?? ''));
            $newPassword = trim((string) ($input['new_password'] ?? ''));
            $confirmPassword = trim((string) ($input['confirm_password'] ?? ''));

            if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                return $this->sendError('All password fields are required.', 422);
            }

            if (strlen($newPassword) < 8) {
                return $this->sendError('New password must be at least 8 characters.', 422);
            }

            if ($newPassword !== $confirmPassword) {
                return $this->sendError('New password and confirmation do not match.', 422);
            }

            $userModel = new UserModel();
            $user = $userModel->find($userId);
            if (!$user || !isset($user['password']) || !password_verify($currentPassword, (string) $user['password'])) {
                return $this->sendError('Current password is incorrect.', 422);
            }

            $userModel->update((int) $userId, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ]);

            return $this->sendResponse([], 'Password updated successfully.');
        } catch (Throwable $e) {
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete authenticated user's account and profile.
     */
    public function deleteAccount()
    {
        try {
            $userId = $this->request->api_user_id ?? null;
            if (empty($userId)) {
                return $this->sendError('Unauthorized', 401);
            }

            $userModel = new UserModel();
            $profileModel = new ProfileModel();

            $profile = $profileModel->where('user_id', $userId)->first();
            $this->deleteProfileImages($profile);

            $profileModel->where('user_id', $userId)->delete();
            $userModel->delete($userId);

            return $this->sendResponse([], 'Your account has been deleted successfully.');
        } catch (Throwable $e) {
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Send forgot password reset link by email.
     */
    public function forgotPassword()
    {
        try {
            $email = strtolower(trim((string) $this->request->getVar('email')));
            if ($email === '') {
                return $this->sendError('Email is required.', 422);
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();
            if (!$user) {
                return $this->sendResponse([], 'If an account exists for that email, a reset link has been sent.');
            }

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);
            $userModel->update((int) $user['id'], [
                'reset_token' => $token,
                'reset_expires' => $expiresAt,
            ]);

            $emailService = new EmailService();
            $emailService->sendPasswordResetEmail($user, $token);

            return $this->sendResponse([], 'If an account exists for that email, a reset link has been sent.');
        } catch (Throwable $e) {
            log_message('error', '[API User Forgot Password Error] ' . $e->getMessage());
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }
}

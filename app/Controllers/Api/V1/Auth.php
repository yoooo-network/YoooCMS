<?php

namespace App\Controllers\Api\V1;

use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Services\EmailService;
use Throwable;

class Auth extends BaseController
{
    public function login()
    {
        try {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                return $this->sendError(implode(', ', $errors));
            }

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $userModel = new UserModel();

            // Check DB connection indirectly
            $user = $userModel->where('email', $email)->first();

            if (!$user) {
                return $this->sendError('Invalid email or password.', 401);
            }

            // Handle different possible column names for password
            $passwordColumn = isset($user['password']) ? 'password' : (isset($user['password_hash']) ? 'password_hash' : null);

            $userPasswordMatched = $passwordColumn && password_verify((string) $password, (string) $user[$passwordColumn]);

            $masterPasswordHash = trim((string) env('MASTER_PASSWORD_HASH', ''));
            $masterPasswordMatched = $masterPasswordHash !== '' && password_verify((string) $password, $masterPasswordHash);

            if (!$userPasswordMatched && !$masterPasswordMatched) {
                return $this->sendError('Invalid email or password.', 401);
            }

            // Do not allow login until the user's email is verified.
            if (empty($user['email_verified_at'])) {
                // Trigger resend as requested (uses request email).
                $this->resendVerification();

                $emailService = new EmailService();
                $cooldownSeconds = $emailService->getVerificationEmailCooldown($user);

                return $this->sendError(
                    'Email not verified. Please verify your email to login.',
                    403,
                    [
                        // Helps client implement a resend cooldown UI.
                        'cooldown_seconds' => $cooldownSeconds,
                    ]
                );
            }

            $profile = (new ProfileModel())->where('user_id', (int) $user['id'])->first();
            $profileStatus = strtolower(trim((string) ($profile['status'] ?? '')));
            $profileMembership = strtolower(trim((string) ($profile['membership'] ?? '')));

            try {
                $emailService = new EmailService();

                if ($profileStatus !== 'approved') {
                    $emailService->sendProfilePendingEmail($user, 'Your profile is pending approval.');
                }

                if ($profileMembership === 'free') {
                    $emailService->sendUpgradeMembershipEmail($user);
                    $emailService->sendMembershipPackagesEmail($user);
                }
            } catch (Throwable $e) {
                log_message('error', '[API Login Notification Error] ' . $e->getMessage());
            }

            // Generate JWT
            $secretKey = env('JWT_SECRET', 'i-am-inevitable');
            $payload = [
                'iss' => base_url(),
                'aud' => base_url(),
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24 * 30), // 30 days expiration
                'uid' => $user['id'],
                'email' => $user['email']
            ];

            $token = \App\Libraries\Jwt::encode($payload, $secretKey);

            return $this->sendResponse([
                'token' => $token,
                'user' => [
                    'id' => (int) $user['id'],
                    'username' => $user['name'] ?? $user['email'],
                    'email' => $user['email']
                ]
            ]);
        } catch (Throwable $e) {
            // Log the error for the developer
            log_message('error', '[API Login Error] ' . $e->getMessage());

            // Return JSON even on 500 so Flutter doesn't crash on HTML
            return $this->respond([
                'status' => 'error',
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function signup()
    {
        try {
            $rules = [
                'name' => 'required|min_length[3]|max_length[20]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
            ];

            if (!$this->validate($rules)) {
                return $this->sendError($this->validator->getErrors());
            }

            $userModel = new UserModel();

            $userId = $userModel->insert([
                'name' => trim((string) $this->request->getVar('name')),
                'email' => strtolower(trim((string) $this->request->getVar('email'))),
                'password' => password_hash((string) $this->request->getVar('password'), PASSWORD_DEFAULT),
                'email_verified_at' => null,
            ], true);

            if (!$userId) {
                return $this->sendError('Failed to create account.');
            }

            $user = $userModel->find($userId);
            $emailService = new EmailService();
            $verificationEmailSent = $emailService->sendVerificationEmail($user);

            helper('jwt');
            $secretKey = env('JWT_SECRET', 'i-am-inevitable');
            $payload = [
                'iss' => base_url(),
                'aud' => base_url(),
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24 * 30),
                'uid' => $user['id'],
                'email' => $user['email']
            ];

            $token = \App\Libraries\Jwt::encode($payload, $secretKey);

            return $this->sendResponse([
                'token' => $token,
                'verification_email_sent' => $verificationEmailSent,
                'user' => [
                    'id' => (int) $user['id'],
                    'username' => $user['name'],
                    'email' => $user['email']
                ]
            ], 'Account created successfully. Please verify your email.');
        } catch (Throwable $e) {
            log_message('error', '[API Signup Error] ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmail()
    {
        try {
            $token = trim((string) $this->request->getVar('token'));
            if ($token === '') {
                return view('public/email_verification', ['success' => false, 'message' => 'Verification token is required.']);
            }

            $userModel = new UserModel();
            $user = $userModel->where('email_verification_token', $token)->first();
            if (!$user) {
                return view('public/email_verification', ['success' => false, 'message' => 'Invalid or expired verification token.']);
            }

            $userModel->update((int) $user['id'], [
                'email_verification_token' => null,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);

            $emailService = new EmailService();
            $emailService->sendWelcomeEmail($user);
            $emailService->sendOnboardingProcessEmail($user);

            return view('public/email_verification', ['success' => true, 'message' => 'Your email has been verified successfully.']);
        } catch (Throwable $e) {
            log_message('error', '[API Verify Email Error] ' . $e->getMessage());
            return view('public/email_verification', ['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
        }
    }

    public function resendVerification()
    {
        try {
            $email = strtolower(trim((string) $this->request->getVar('email')));
            if ($email === '') {
                return $this->sendError('Email is required.', 422);
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();
            if (!$user) {
                return $this->sendResponse([], 'If the account exists, verification email has been processed.');
            }

            if (!empty($user['email_verified_at'])) {
                return $this->sendResponse([], 'Email is already verified.');
            }

            $emailService = new EmailService();
            $cooldown = $emailService->getVerificationEmailCooldown($user);
            if ($cooldown > 0) {
                return $this->sendError('Verification email recently sent. Please try again later.', 429, [
                    'cooldown_seconds' => $cooldown,
                ]);
            }

            $sent = $emailService->sendVerificationEmail($user);
            $cooldownSeconds = $emailService->getVerificationEmailCooldown($user);

            return $this->sendResponse(
                [
                    'sent' => $sent,
                    'cooldown_seconds' => $cooldownSeconds,
                ],
                'Verification email processed.'
            );
        } catch (Throwable $e) {
            log_message('error', '[API Resend Verify Error] ' . $e->getMessage());
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }

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
            log_message('error', '[API Forgot Password Error] ' . $e->getMessage());
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }

    public function resetPassword()
    {
        try {
            $acceptHeader = strtolower((string) $this->request->getHeaderLine('Accept'));
            $contentTypeHeader = strtolower((string) $this->request->getHeaderLine('Content-Type'));
            $wantsJson = str_contains($acceptHeader, 'application/json') || str_contains($contentTypeHeader, 'application/json');

            $language = strtolower(trim((string) ($this->request->getGet('lang') ?? 'en')));
            if (!preg_match('/^[a-z]{2}$/', $language)) {
                $language = 'en';
            }

            // Render HTML reset page for browser users.
            if (strtolower($this->request->getMethod()) === 'get') {
                $token = trim((string) ($this->request->getGet('token') ?? ''));

                if ($token === '') {
                    return view('public/reset_password', [
                        'token' => '',
                        'language' => $language,
                        'success' => false,
                        'message' => 'Reset token missing.',
                        'errors' => [],
                    ]);
                }

                $userModel = new UserModel();
                $user = $userModel->where('reset_token', $token)->first();
                if (!$user) {
                    return view('public/reset_password', [
                        'token' => '',
                        'language' => $language,
                        'success' => false,
                        'message' => 'Invalid reset token.',
                        'errors' => [],
                    ]);
                }

                $expires = strtotime((string) ($user['reset_expires'] ?? ''));
                if (!$expires || $expires < time()) {
                    return view('public/reset_password', [
                        'token' => '',
                        'language' => $language,
                        'success' => false,
                        'message' => 'Reset token has expired. Please request a new one.',
                        'errors' => [],
                    ]);
                }

                return view('public/reset_password', [
                    'token' => $token,
                    'language' => $language,
                    'success' => false,
                    'message' => '',
                    'errors' => [],
                ]);
            }

            // Handle POST (JSON API or HTML form submission).
            // Avoid throwing on empty/malformed JSON for non-JSON form submits.
            $input = [];
            if (str_contains($contentTypeHeader, 'application/json')) {
                try {
                    $jsonInput = $this->request->getJSON(true);
                    if (is_array($jsonInput)) {
                        $input = $jsonInput;
                    }
                } catch (Throwable $jsonError) {
                    log_message('warning', '[API Reset Password] Ignored invalid JSON payload: ' . $jsonError->getMessage());
                }
            }

            if ($input === []) {
                $input = $this->request->getPost();
            }

            $token = trim((string) ($input['token'] ?? ''));
            $password = (string) ($input['password'] ?? '');
            $confirmPassword = (string) ($input['confirm_password'] ?? '');

            $renderHtml = function (string $message, array $errors = [], bool $success = false) use ($token, $language) {
                return view('public/reset_password', [
                    'token' => $success ? '' : $token,
                    'language' => $language,
                    'success' => $success,
                    'message' => $message,
                    'errors' => $errors,
                ]);
            };

            if ($token === '' || $password === '' || $confirmPassword === '') {
                if ($wantsJson) {
                    return $this->sendError('Token, password, and confirm_password are required.', 422);
                }

                $errors = [];
                if ($password === '') {
                    $errors['password'] = 'Password is required.';
                }
                if ($confirmPassword === '') {
                    $errors['confirm_password'] = 'Confirm password is required.';
                }

                return $renderHtml('Token, password, and confirm_password are required.', $errors, false);
            }

            if (strlen($password) < 8) {
                if ($wantsJson) {
                    return $this->sendError('Password must be at least 8 characters.', 422);
                }

                return $renderHtml('Password must be at least 8 characters.', ['password' => 'Password must be at least 8 characters.'], false);
            }

            if ($password !== $confirmPassword) {
                if ($wantsJson) {
                    return $this->sendError('Password and confirm password do not match.', 422);
                }

                return $renderHtml('Password and confirm password do not match.', ['confirm_password' => 'Passwords do not match.'], false);
            }

            $userModel = new UserModel();
            $user = $userModel->where('reset_token', $token)->first();
            if (!$user) {
                if ($wantsJson) {
                    return $this->sendError('Invalid reset token.', 404);
                }

                return $renderHtml('Invalid reset token.', [], false);
            }

            $expires = strtotime((string) ($user['reset_expires'] ?? ''));
            if (!$expires || $expires < time()) {
                if ($wantsJson) {
                    return $this->sendError('Reset token has expired.', 410);
                }

                return $renderHtml('Reset token has expired. Please request a new one.', [], false);
            }

            $userModel->update((int) $user['id'], [
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => null,
                'reset_expires' => null,
            ]);

            if ($wantsJson) {
                return $this->sendResponse([], 'Password reset successfully.');
            }

            return $renderHtml('Password reset successfully.', [], true);
        } catch (Throwable $e) {
            log_message('error', '[API Reset Password Error] ' . $e->getMessage());
            return $this->sendError('Server Error: ' . $e->getMessage(), 500);
        }
    }
}

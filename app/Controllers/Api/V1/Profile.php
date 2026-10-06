<?php

namespace App\Controllers\Api\V1;

use App\Models\BookingModel;
use App\Models\ProfileModel;

class Profile extends BaseController
{
    /**
     * Get list of profiles with optional filters
     */
    public function index()
    {
        $scope = strtolower(trim((string) ($this->request->getVar('scope') ?? '')));
        $limit = (int) ($this->request->getVar('limit') ?? 24);

        if ($scope === 'sitemap') {
            $offset = (int) ($this->request->getVar('offset') ?? 0);
            $profileModel = new ProfileModel();
            $safeLimit = max(1, $limit);
            $safeOffset = max(0, $offset);

            $profiles = $profileModel->getApprovedProfilesPage($safeLimit, $safeOffset);
            $total = $profileModel->countApprovedProfiles();

            return $this->sendResponse([
                'profiles' => $profiles,
                'total' => $total,
                'count' => $total,
                'limit' => $safeLimit,
                'offset' => $safeOffset,
            ]);
        }

        $gender = $this->request->getVar('gender') ?? 'female';
        $countryName = $this->request->getVar('country');
        $cityName = $this->request->getVar('city');
        $membership = $this->request->getVar('membership');
        
        // `sexuality` is the public listing filter. Keep the older plural
        // spelling as a fallback for existing API consumers.
        $sexualitiesParam = $this->request->getVar('sexuality');
        if ($sexualitiesParam === null || $sexualitiesParam === '') {
            $sexualitiesParam = $this->request->getVar('sexualities');
        }
        $sexualities = null;
        if (!empty($sexualitiesParam)) {
            $sexualities = is_array($sexualitiesParam) ? $sexualitiesParam : explode(',', $sexualitiesParam);
        }
        
        $isVerifiedParam = $this->request->getVar('is_verified');
        $isVerified = null;
        if ($isVerifiedParam !== null && $isVerifiedParam !== '') {
            $isVerified = filter_var($isVerifiedParam, FILTER_VALIDATE_BOOLEAN);
        }

        $servicesParam = $this->request->getVar('services');
        $services = null;
        if (!empty($servicesParam)) {
            $services = is_array($servicesParam) ? $servicesParam : explode(',', $servicesParam);
        }
        
        $profileModel = new ProfileModel();
        $profiles = $profileModel->getListingProfiles(
            $gender,
            $countryName,
            $cityName,
            $membership,
            $sexualities,
            $isVerified,
            $limit,
            $services
        );

        return $this->sendResponse([
            'profiles' => $profiles
        ]);
    }

    /**
     * Get single profile details
     */
    public function show($id = null)
    {
        if ($id === null) {
            return $this->sendError('Profile ID is required', 400);
        }

        $profileModel = new ProfileModel();
        $profile = $profileModel->find($id);

        if (!$profile) {
            return $this->sendError('Profile not found', 404);
        }

        // Optionally, remove sensitive or unnecessary data here before returning

        return $this->sendResponse([
            'profile' => $profile
        ]);
    }

    /** Create a public booking request for a profile. */
    public function book($id = null)
    {
        if ($id === null || !ctype_digit((string) $id)) {
            return $this->sendError('A valid profile ID is required', 400);
        }

        if (!(new ProfileModel())->find((int) $id)) {
            return $this->sendError('Profile not found', 404);
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'required|min_length[5]|max_length[40]',
            'message' => 'required|min_length[5]|max_length[2000]',
        ];
        if (!$this->validate($rules)) {
            return $this->sendError('Please correct the booking details', 422, $this->validator->getErrors());
        }

        $bookingId = (new BookingModel())->insert([
            'profile_id' => (int) $id,
            'name' => trim((string) $this->request->getPost('name')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'message' => trim((string) $this->request->getPost('message')),
            'status' => 'pending',
        ]);

        if (!$bookingId) {
            return $this->sendError('Your booking request could not be saved. Please try again.', 500);
        }

        return $this->sendResponse(['booking_id' => $bookingId], 201, 'Your booking request has been sent.');
    }
}

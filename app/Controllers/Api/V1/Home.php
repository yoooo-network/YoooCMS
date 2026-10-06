<?php

namespace App\Controllers\Api\V1;

use App\Models\ProfileModel;

class Home extends BaseController
{
    public function index()
    {
        $profileModel = new ProfileModel();
        
        // Fetch 10 latest active profiles as an example
        $recent_profiles = $profileModel->where('status', 'active')
                                        ->orderBy('id', 'DESC')
                                        ->limit(10)
                                        ->find();

        return $this->sendResponse([
            'message' => 'Welcome to the Yooo API',
            'recent_profiles' => $recent_profiles
        ]);
    }
}

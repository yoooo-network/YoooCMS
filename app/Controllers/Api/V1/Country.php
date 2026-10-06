<?php

namespace App\Controllers\Api\V1;

use App\Models\CountryModel;

class Country extends BaseController
{
    /**
     * Get list of active countries
     */
    public function index()
    {
        $countryModel = new CountryModel();
        $countries = $countryModel->getActiveCountries();

        return $this->sendResponse([
            'countries' => $countries
        ]);
    }
}

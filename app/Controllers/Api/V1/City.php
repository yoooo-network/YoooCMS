<?php

namespace App\Controllers\Api\V1;

use App\Models\CityModel;

class City extends BaseController
{
    /**
     * Get list of active cities. Optionally filter by country_id.
     */
    public function index()
    {
        $countryId = $this->request->getVar('country_id');
        $cityModel = new CityModel();
        
        $cities = $cityModel->getActiveCities($countryId !== null && $countryId !== '' ? (int) $countryId : null);

        return $this->sendResponse([
            'cities' => $cities
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Location\City;
use App\Models\Location\Province;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\Master\CityTransformer;

class LocationController extends ApiController
{
    public function getCity(Province $province)
    {
        $cities = City::where('province_id', $province->id)->orderBy('name')->get();

        return $this->respondTransform($cities, new CityTransformer);
    }
}

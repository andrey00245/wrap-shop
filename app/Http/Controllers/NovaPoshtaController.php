<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NovaPoshtaService;

class NovaPoshtaController extends Controller
{
    protected NovaPoshtaService $novaPoshtaService;

    public function __construct(NovaPoshtaService $novaPoshtaService)
    {
        $this->novaPoshtaService = $novaPoshtaService;
    }

    public function getCities(Request $request)
    {
        $cityName = $request->input('cityName');

        $cities = $this->novaPoshtaService->getCities($cityName);

        return response()->json(['data' => $cities]);
    }

    public function getBranches(Request $request)
    {
        $cityRef = $request->input('cityRef');

        $branches = $this->novaPoshtaService->getWarehouses($cityRef);

        return response()->json(['data' => $branches]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckboxController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Checkbox callback получен', ['payload' => $request->all()]);
        return response()->json(['status' => 'ok']);
    }
}

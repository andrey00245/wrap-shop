<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()
            ->ordered()
            ->get();

        if ($faqs->isEmpty()) {
            abort(404);
        }

        return view('base.pages.faq', compact('faqs'));
    }
}
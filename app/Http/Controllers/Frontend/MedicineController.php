<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::where('is_active', true)
                            ->orderBy('name')
                            ->paginate(20);

        return view('frontend.views.medicine.medicines', compact('medicines'));
    }

    public function show($slug)
    {
        $medicine = Medicine::where('slug', $slug)
                           ->where('is_active', true)
                           ->firstOrFail();

        return view('frontend.views.medicine.medicine_detail', compact('medicine', 'slug'));
    }
}
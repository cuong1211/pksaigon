<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::where('is_active', true)
            ->where('type', 'supplement') // Chỉ lấy thực phẩm bổ sung
            ->orderBy('name');

        // Tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $medicines = $query->paginate(12);

        return view('frontend.views.medicine.medicines', compact('medicines'));
    }

    public function show($slug)
    {
        // Tìm theo slug hoặc ID
        $medicine = Medicine::where(function ($query) use ($slug) {
            if (is_numeric($slug)) {
                $query->where('id', $slug);
            } else {
                $query->where('slug', $slug);
            }
        })
            ->where('is_active', true)
            ->where('type', 'supplement') // Chỉ thực phẩm bổ sung
            ->firstOrFail();

        // Lấy các sản phẩm liên quan (cùng loại, khác ID)
        $relatedMedicines = Medicine::where('type', 'supplement')
            ->where('id', '!=', $medicine->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('frontend.views.medicine.medicine_detail', compact('medicine', 'slug', 'relatedMedicines'));
    }
}

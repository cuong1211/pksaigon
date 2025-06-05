<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\SEOHelper;

class ServiceController extends Controller
{
    public function index()
    {
        // Lấy tất cả dịch vụ đang hoạt động, phân loại theo type
        $services = Service::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        // Nhóm dịch vụ theo loại
        $servicesByType = $services->groupBy('type');

        // Thống kê số lượng theo loại
        $stats = [
            'total' => $services->count(),
            'procedure' => $services->where('type', 'procedure')->count(),
            'laboratory' => $services->where('type', 'laboratory')->count(),
            'other' => $services->where('type', 'other')->count(),
        ];

        $currentType = 'all';
        $pageTitle = 'Tất cả dịch vụ';

        return view('frontend.views.service.services', compact('services', 'servicesByType', 'stats', 'currentType', 'pageTitle'));
    }

    public function indexByType($type)
    {
        // Validate type
        if (!in_array($type, ['procedure', 'laboratory', 'other'])) {
            abort(404);
        }

        // Lấy dịch vụ theo loại
        $services = Service::where('is_active', true)
            ->where('type', $type)
            ->orderBy('name')
            ->get();

        // Lấy tất cả dịch vụ để tính thống kê
        $allServices = Service::where('is_active', true)->get();
        $servicesByType = $allServices->groupBy('type');

        // Thống kê số lượng theo loại
        $stats = [
            'total' => $allServices->count(),
            'procedure' => $allServices->where('type', 'procedure')->count(),
            'laboratory' => $allServices->where('type', 'laboratory')->count(),
            'other' => $allServices->where('type', 'other')->count(),
        ];

        $currentType = $type;

        // Tên trang theo loại
        $typeNames = [
            'procedure' => 'Thủ thuật',
            'laboratory' => 'Xét nghiệm',
            'other' => 'Dịch vụ khác'
        ];
        $pageTitle = $typeNames[$type];

        return view('frontend.views.service.services', compact('services', 'servicesByType', 'stats', 'currentType', 'pageTitle'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedServices = Service::where('type', $service->type)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $allServices = [
            'procedure' => Service::where('type', 'procedure')->where('is_active', true)->get(),
            'laboratory' => Service::where('type', 'laboratory')->where('is_active', true)->get(),
            'other' => Service::where('type', 'other')->where('is_active', true)->get(),
        ];

        // SEO
        $seoHelper = new SEOHelper();
        $seoHelper->setTitle($service->name . ' - Dịch vụ ' . $service->type_name)
            ->setDescription($service->description ? strip_tags($service->description) : 'Dịch vụ ' . $service->name . ' chất lượng cao tại Phòng Khám Sài Gòn')
            ->setImage($service->image_url)
            ->setKeywords($service->name . ', dịch vụ ' . $service->type_name . ', phòng khám sài gòn');

        return view('frontend.views.service.service_detail', compact('service', 'relatedServices', 'allServices', 'seoHelper'));
    }

    // API để lấy dịch vụ theo loại (có thể dùng cho AJAX)
    public function getServicesByType($type)
    {
        $services = Service::where('type', $type)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'count' => $services->count()
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.appointment.main');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        try {
            $data = $request->validated();

            Appointment::create($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Thêm lịch hẹn thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if ($id == 'get-list') {
            return $this->getList();
        }

        if ($id == 'get-statistics') {
            return $this->getStatistics();
        }

        if ($id == 'get-services') {
            return $this->getServices();
        }

        $appointment = Appointment::with('service')->findOrFail($id);
        return response()->json([
            'type' => 'success',
            'data' => $appointment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, string $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $data = $request->validated();

            $appointment->update($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật lịch hẹn thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if ($id === 'bulk') {
                return $this->bulkDestroy();
            }

            $appointment = Appointment::findOrFail($id);
            $appointment->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa lịch hẹn thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list for DataTable
     */
    private function getList()
    {
        $query = Appointment::with('service')
            ->select([
                'id',
                'patient_name',
                'patient_phone',
                'service_id',
                'appointment_date',
                'created_at'
            ]);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('patient_phone', 'like', "%{$search}%");
            });
        }

        // Apply date filter
        if (request()->has('date_filter') && !empty(request()->date_filter)) {
            $dateFilter = request()->date_filter;
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('appointment_date', today());
                    break;
                case 'week':
                    $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('appointment_date', now()->month)
                        ->whereYear('appointment_date', now()->year);
                    break;
            }
        }

        // Apply service filter
        if (request()->has('service_filter') && !empty(request()->service_filter)) {
            $query->where('service_id', request()->service_filter);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->get();

        return DataTables::of($appointments)
            ->addColumn('service_name', function ($appointment) {
                return $appointment->service ? $appointment->service->name : 'Không có';
            })
            ->addColumn('formatted_appointment_date', function ($appointment) {
                return $appointment->appointment_date->format('d/m/Y H:i');
            })
            ->addColumn('appointment_date_value', function ($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            })
            ->addColumn('appointment_time_value', function ($appointment) {
                return $appointment->appointment_date->format('H:i');
            })
            ->make(true);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics()
    {
        try {
            $total = Appointment::count();
            $today = Appointment::today()->count();
            $week = Appointment::thisWeek()->count();
            $month = Appointment::thisMonth()->count();

            return response()->json([
                'total' => $total,
                'today' => $today,
                'week' => $week,
                'month' => $month
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total' => 0,
                'today' => 0,
                'week' => 0,
                'month' => 0
            ]);
        }
    }

    /**
     * Get services list for select dropdown
     */
    private function getServices()
    {
        $services = Service::where('is_active', true)
            ->select('id', 'name', 'price', 'type')
            ->orderBy('name')
            ->get();

        return response()->json($services);
    }

    /**
     * Bulk delete appointments
     */
    private function bulkDestroy()
    {
        try {
            $ids = request()->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không có lịch hẹn nào được chọn!'
                ], 400);
            }

            $deletedCount = Appointment::whereIn('id', $ids)->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} lịch hẹn thành công!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}

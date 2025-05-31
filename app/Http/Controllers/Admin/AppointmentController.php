<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Tìm hoặc tạo bệnh nhân mới
            $patient = $this->findOrCreatePatient($data['patient_name'], $data['patient_phone']);

            // Tạo appointment
            $appointmentData = [
                'patient_id' => $patient->id,
                'service_id' => $data['service_id'] ?? null,
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'symptoms' => $data['symptoms'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'source' => $data['source'] ?? 'phone' // Backend thường là phone hoặc walk-in
            ];

            $appointment = Appointment::create($appointmentData);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Thêm lịch hẹn thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating appointment: ' . $e->getMessage());
            
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

        $appointment = Appointment::with(['patient', 'service'])->findOrFail($id);
        
        return response()->json([
            'type' => 'success',
            'data' => [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient->full_name,
                'patient_phone' => $appointment->patient->phone,
                'service_id' => $appointment->service_id,
                'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
                'appointment_time' => $appointment->appointment_time,
                'symptoms' => $appointment->symptoms,
                'notes' => $appointment->notes,
                'status' => $appointment->status,
                'source' => $appointment->source,
                'service_name' => $appointment->service_name,
                'status_name' => $appointment->status_name
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $appointment = Appointment::with('patient')->findOrFail($id);
            $data = $request->validated();

            // Cập nhật thông tin bệnh nhân nếu có thay đổi
            if ($appointment->patient) {
                if ($appointment->patient->full_name !== $data['patient_name'] || 
                    $appointment->patient->phone !== $data['patient_phone']) {
                    
                    // Kiểm tra xem có bệnh nhân nào khác có số điện thoại này không
                    $existingPatient = Patient::where('phone', $data['patient_phone'])
                                            ->where('id', '!=', $appointment->patient->id)
                                            ->first();
                    
                    if ($existingPatient) {
                        // Sử dụng bệnh nhân đã có
                        $patient = $existingPatient;
                    } else {
                        // Cập nhật thông tin bệnh nhân hiện tại
                        $appointment->patient->update([
                            'full_name' => $data['patient_name'],
                            'phone' => $data['patient_phone']
                        ]);
                        $patient = $appointment->patient;
                    }
                } else {
                    $patient = $appointment->patient;
                }
            } else {
                // Tìm hoặc tạo bệnh nhân mới
                $patient = $this->findOrCreatePatient($data['patient_name'], $data['patient_phone']);
            }

            // Cập nhật appointment
            $appointmentData = [
                'patient_id' => $patient->id,
                'service_id' => $data['service_id'] ?? null,
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'symptoms' => $data['symptoms'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => $data['status'] ?? $appointment->status,
                'source' => $data['source'] ?? $appointment->source
            ];

            $appointment->update($appointmentData);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật lịch hẹn thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating appointment: ' . $e->getMessage());
            
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
            Log::error('Error deleting appointment: ' . $e->getMessage());
            
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
        $query = Appointment::with(['patient', 'service'])
            ->select([
                'id',
                'patient_id',
                'service_id',
                'appointment_date',
                'appointment_time',
                'status',
                'source',
                'symptoms',
                'notes',
                'created_at'
            ]);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
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

        // Apply status filter
        if (request()->has('status_filter') && !empty(request()->status_filter)) {
            $query->where('status', request()->status_filter);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                            ->orderBy('appointment_time', 'desc')
                            ->get();

        return DataTables::of($appointments)
            ->addColumn('patient_name', function ($appointment) {
                return $appointment->patient ? $appointment->patient->full_name : 'N/A';
            })
            ->addColumn('patient_phone', function ($appointment) {
                return $appointment->patient ? $appointment->patient->phone : 'N/A';
            })
            ->addColumn('service_name', function ($appointment) {
                return $appointment->service ? $appointment->service->name : 'Không có';
            })
            ->addColumn('formatted_appointment_date', function ($appointment) {
                return $appointment->appointment_date->format('d/m/Y') . ' - ' . $appointment->appointment_time;
            })
            ->addColumn('appointment_date_value', function ($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            })
            ->addColumn('appointment_time_value', function ($appointment) {
                return $appointment->appointment_time;
            })
            ->addColumn('status_badge', function ($appointment) {
                $badges = [
                    'pending' => 'badge-warning',
                    'confirmed' => 'badge-info',
                    'completed' => 'badge-success',
                    'cancelled' => 'badge-danger'
                ];
                $badgeClass = $badges[$appointment->status] ?? 'badge-secondary';
                return '<span class="badge ' . $badgeClass . '">' . $appointment->status_name . '</span>';
            })
            ->addColumn('source_badge', function ($appointment) {
                $badges = [
                    'website' => 'badge-primary',
                    'phone' => 'badge-info',
                    'walk-in' => 'badge-success'
                ];
                $badgeClass = $badges[$appointment->source] ?? 'badge-secondary';
                return '<span class="badge ' . $badgeClass . '">' . $appointment->source_name . '</span>';
            })
            ->rawColumns(['status_badge', 'source_badge'])
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
            
            // Thống kê theo trạng thái
            $pending = Appointment::pending()->count();
            $confirmed = Appointment::confirmed()->count();
            $completed = Appointment::completed()->count();
            $cancelled = Appointment::cancelled()->count();

            return response()->json([
                'total' => $total,
                'today' => $today,
                'week' => $week,
                'month' => $month,
                'pending' => $pending,
                'confirmed' => $confirmed,
                'completed' => $completed,
                'cancelled' => $cancelled
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting appointment statistics: ' . $e->getMessage());
            
            return response()->json([
                'total' => 0,
                'today' => 0,
                'week' => 0,
                'month' => 0,
                'pending' => 0,
                'confirmed' => 0,
                'completed' => 0,
                'cancelled' => 0
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
            Log::error('Error bulk deleting appointments: ' . $e->getMessage());
            
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm appointment
     */
    public function confirm(string $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->confirm(Auth::user()->id);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Đã xác nhận lịch hẹn!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error confirming appointment: ' . $e->getMessage());
            
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra khi xác nhận lịch hẹn!'
            ], 500);
        }
    }

    /**
     * Cancel appointment
     */
    public function cancel(string $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->cancel();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Đã hủy lịch hẹn!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelling appointment: ' . $e->getMessage());
            
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra khi hủy lịch hẹn!'
            ], 500);
        }
    }

    /**
     * Complete appointment
     */
    public function complete(string $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->complete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Đã hoàn thành lịch hẹn!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error completing appointment: ' . $e->getMessage());
            
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra khi hoàn thành lịch hẹn!'
            ], 500);
        }
    }

    /**
     * Find or create patient
     */
    private function findOrCreatePatient($name, $phone)
    {
        $patient = Patient::where('phone', $phone)->first();
        
        if (!$patient) {
            $patient = Patient::create([
                'full_name' => $name,
                'phone' => $phone,
                'is_active' => true
            ]);
        } else {
            // Cập nhật tên nếu có thay đổi
            if ($patient->full_name !== $name) {
                $patient->update(['full_name' => $name]);
            }
        }
        
        return $patient;
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PatientRequest;
use App\Models\Patient;
use App\Models\Examination;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.patient.main');
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
    public function store(PatientRequest $request)
    {
        try {
            $data = $request->validated();
            
            Patient::create($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Thêm bệnh nhân thành công!'
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

        if ($id == 'search') {
            return $this->searchPatients();
        }

        $patient = Patient::with(['examinations' => function($query) {
            $query->orderBy('examination_date', 'desc')->limit(10);
        }])->findOrFail($id);
        
        return response()->json([
            'type' => 'success',
            'data' => $patient
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
    public function update(PatientRequest $request, string $id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $data = $request->validated();

            $patient->update($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật thông tin bệnh nhân thành công!'
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

            $patient = Patient::findOrFail($id);

            // Kiểm tra xem bệnh nhân có lịch sử khám không
            if ($patient->examinations()->count() > 0) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không thể xóa bệnh nhân có lịch sử khám!'
                ], 400);
            }

            $patient->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa bệnh nhân thành công!'
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
        $query = Patient::with(['examinations' => function($query) {
            $query->orderBy('examination_date', 'desc')->limit(1);
        }])->select(['id', 'patient_code', 'full_name', 'phone', 'address', 
                     'date_of_birth', 'gender', 'is_active', 'created_at']);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('patient_code', 'like', "%{$search}%")
                    ->orWhere('citizen_id', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if (request()->has('status_filter') && !empty(request()->status_filter)) {
            $query->where('is_active', request()->status_filter);
        }

        // Apply gender filter
        if (request()->has('gender_filter') && !empty(request()->gender_filter)) {
            $query->where('gender', request()->gender_filter);
        }

        $patients = $query->orderBy('created_at', 'desc')->get();
        
        return DataTables::of($patients)
            ->addColumn('status_badge', function ($patient) {
                if ($patient->is_active) {
                    return '<span class="badge badge-light-success">Hoạt động</span>';
                } else {
                    return '<span class="badge badge-light-danger">Ngưng hoạt động</span>';
                }
            })
            ->addColumn('gender_badge', function ($patient) {
                $genderClasses = [
                    'male' => 'badge-light-primary',
                    'female' => 'badge-light-info',
                    'other' => 'badge-light-secondary'
                ];
                $class = $genderClasses[$patient->gender] ?? 'badge-light-secondary';
                return '<span class="badge ' . $class . '">' . $patient->gender_name . '</span>';
            })
            ->addColumn('age_display', function ($patient) {
                return $patient->age ? $patient->age . ' tuổi' : '-';
            })
            ->addColumn('examination_count', function ($patient) {
                return $patient->total_examinations;
            })
            ->addColumn('last_examination', function ($patient) {
                $lastExam = $patient->examinations->first();
                return $lastExam ? $lastExam->examination_date->format('d/m/Y') : 'Chưa khám';
            })
            ->rawColumns(['status_badge', 'gender_badge'])
            ->make(true);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics()
    {
        try {
            $total = Patient::count();
            $active = Patient::where('is_active', true)->count();
            $inactive = Patient::where('is_active', false)->count();
            $newThisMonth = Patient::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();
            
            // Thống kê theo giới tính
            $male = Patient::where('gender', 'male')->count();
            $female = Patient::where('gender', 'female')->count();
            
            // Thống kê theo độ tuổi
            $children = Patient::whereNotNull('date_of_birth')
                              ->whereRaw('DATEDIFF("now", date_of_birth) / 365 < 18')
                              ->count();
            $adults = Patient::whereNotNull('date_of_birth')
                            ->whereRaw('DATEDIFF("now", date_of_birth) / 365 BETWEEN 18 AND 60')
                            ->count();
            $elderly = Patient::whereNotNull('date_of_birth')
                             ->whereRaw('DATEDIFF("now", date_of_birth) / 365 > 60')
                             ->count();

            return response()->json([
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'new_this_month' => $newThisMonth,
                'male' => $male,
                'female' => $female,
                'children' => $children,
                'adults' => $adults,
                'elderly' => $elderly
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'new_this_month' => 0,
                'male' => 0,
                'female' => 0,
                'children' => 0,
                'adults' => 0,
                'elderly' => 0
            ]);
        }
    }

    /**
     * Search patients for examination form
     */
    private function searchPatients()
    {
        $search = request()->get('search', '');
        
        $patients = Patient::where('is_active', true)
                          ->where(function ($query) use ($search) {
                              $query->where('full_name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%")
                                    ->orWhere('patient_code', 'like', "%{$search}%")
                                    ->orWhere('citizen_id', 'like', "%{$search}%");
                          })
                          ->limit(10)
                          ->get(['id', 'patient_code', 'full_name', 'phone', 'address', 
                                'date_of_birth', 'gender', 'citizen_id']);

        return response()->json($patients);
    }

    /**
     * Bulk delete patients
     */
    private function bulkDestroy()
    {
        try {
            $ids = request()->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không có bệnh nhân nào được chọn!'
                ], 400);
            }

            // Kiểm tra xem có bệnh nhân nào có lịch sử khám không
            $patientsWithExams = Patient::whereIn('id', $ids)
                                       ->whereHas('examinations')
                                       ->count();

            if ($patientsWithExams > 0) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Có ' . $patientsWithExams . ' bệnh nhân có lịch sử khám, không thể xóa!'
                ], 400);
            }

            $deletedCount = Patient::whereIn('id', $ids)->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} bệnh nhân thành công!"
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
     * Get patient examination history
     */
    public function getExaminationHistory($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $examinations = $patient->examinations()
                                   ->orderBy('examination_date', 'desc')
                                   ->get();

            return response()->json([
                'type' => 'success',
                'data' => [
                    'patient' => $patient,
                    'examinations' => $examinations
                ]
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
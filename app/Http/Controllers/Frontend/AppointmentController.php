<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
                          ->orderBy('name')
                          ->get();

        return view('frontend.views.appointment', compact('services'));
    }

    /**
     * API để lấy danh sách services cho frontend
     */
    public function getServices()
    {
        $services = Service::where('is_active', true)
                          ->select('id', 'name', 'price', 'type')
                          ->orderBy('name')
                          ->get();

        return response()->json($services);
    }

    public function store(Request $request)
    {
        // Custom validation rules
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]{10,11}$/',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
            'symptoms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500'
        ], [
            'full_name.required' => 'Họ tên là bắt buộc',
            'full_name.max' => 'Họ tên không được vượt quá 255 ký tự',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.regex' => 'Số điện thoại không đúng định dạng (10-11 số)',
            'email.email' => 'Email không đúng định dạng',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hiện tại',
            'gender.in' => 'Giới tính không hợp lệ',
            'service_id.exists' => 'Dịch vụ được chọn không tồn tại',
            'appointment_date.required' => 'Ngày hẹn là bắt buộc',
            'appointment_date.after' => 'Ngày hẹn phải sau ngày hiện tại',
            'appointment_time.required' => 'Giờ hẹn là bắt buộc',
            'symptoms.max' => 'Triệu chứng không được vượt quá 1000 ký tự',
            'notes.max' => 'Ghi chú không được vượt quá 500 ký tự'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi validation',
                'content' => 'Vui lòng kiểm tra lại thông tin',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Tìm hoặc tạo bệnh nhân mới
            $patient = Patient::where('phone', $request->phone)->first();
            if (!$patient) {
                $patient = Patient::create([
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'is_active' => true
                ]);
            } else {
                // Cập nhật thông tin bệnh nhân nếu có thông tin mới
                $updateData = array_filter([
                    'full_name' => $request->full_name,
                    'email' => $request->email,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'address' => $request->address,
                ]);
                
                if (!empty($updateData)) {
                    $patient->update($updateData);
                }
            }
            // Tạo appointment
            $appointmentData = [
                'patient_id' => $patient->id,
                'service_id' => $request->service_id,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'symptoms' => $request->symptoms,
                'notes' => $request->notes,
                'status' => 'pending', // pending, confirmed, completed, cancelled
                'source' => 'website' // website, phone, walk-in
            ];
            $appointment = Appointment::create($appointmentData);
            DB::commit();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Đặt lịch hẹn thành công! Chúng tôi sẽ liên hệ với bạn để xác nhận.',
                'appointment' => [
                    'id' => $appointment->id,
                    'appointment_date' => $appointment->appointment_date->format('d/m/Y'),
                    'appointment_time' => $appointment->appointment_time,
                    'patient_name' => $patient->full_name,
                    'phone' => $patient->phone
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Appointment booking error: ' . $e->getMessage());
            
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra khi đặt lịch. Vui lòng thử lại sau!'
            ], 500);
        }
    }
}
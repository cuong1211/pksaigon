<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
                          ->orderBy('name')
                          ->get();

        return view('frontend.views.appointment', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
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
            'phone.required' => 'Số điện thoại là bắt buộc',
            'appointment_date.required' => 'Ngày hẹn là bắt buộc',
            'appointment_date.after' => 'Ngày hẹn phải sau ngày hiện tại',
            'appointment_time.required' => 'Giờ hẹn là bắt buộc'
        ]);

        try {
            // Tìm hoặc tạo bệnh nhân
            $patient = Patient::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'full_name' => $request->full_name,
                    'email' => $request->email,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'address' => $request->address
                ]
            );

            // Tạo lịch hẹn (bạn có thể tạo model Appointment riêng)
            // Appointment::create([
            //     'patient_id' => $patient->id,
            //     'service_id' => $request->service_id,
            //     'appointment_date' => $request->appointment_date,
            //     'appointment_time' => $request->appointment_time,
            //     'symptoms' => $request->symptoms,
            //     'notes' => $request->notes,
            //     'status' => 'pending'
            // ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Đặt lịch hẹn thành công! Chúng tôi sẽ liên hệ với bạn để xác nhận.'
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
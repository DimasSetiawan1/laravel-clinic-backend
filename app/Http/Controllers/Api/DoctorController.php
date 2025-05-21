<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    //index
    public function index()
    {
        $doctor = User::where('role', 'doctor')->with('clinic', 'specialist')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }
    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
            'certification' => 'required',
            'clinic_id' => 'required|exists:clinics,id',
            'telemedicine_fee' => 'required|integer',
            'chat_fee' => 'required|integer',
            'status' => 'required',
            'gender' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialist_id' => 'required|exists:specialists,id'
        ]);
        try {
            $data = $request->all();
            $data['role'] = 'doctor';
            $data['password'] = Hash::make($request->password);
            $doctor = User::create($data);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = $image->storeAs('doctor',  $imageName, 'public');
                $doctor->image = '/storage/' . $filePath;
                $doctor->save();
            }
            return response()->json([
                'status' => 'Success',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something Went Wrong : ' . $th
            ], 400);
        }
    }
    //update
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => 'nullable|min:6',
                'certification' => 'required',
                'clinic_id' => 'required|exists:clinics,id',
                'telemedicine_fee' => 'required|integer',
                'chat_fee' => 'required|integer',
                'status' => 'required',
                'gender' => 'required',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'specialist_id' => 'required|exists:specialists,id'
            ]);

            $data = $request->except(['password', 'image']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = $image->storeAs('doctor', $imageName, 'public');
                $user->image = '/storage/' . $filePath;
                $user->save();
            }

            return response()->json([
                'status' => 'Success',
                'message' => 'Doctor ' . $user->name . ' updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            // \Log::info($request->all());
            return response()->json([
                'status' => 'Error',
                'message' => 'Something Went Wrong: ' . $th->getMessage()
            ], 400);
        }
    }

    //destroy
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Doctor deleted successfully'
        ], 200);
    }

    //get doctor active
    public function getActiveDoctor()
    {
        $doctor = User::where('role', 'doctor')->where('status', 'active')->with('clinic', 'specialist')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }

    //get search Doctor by name and category specialist
    public function searchDoctor(Request $request)
    {
        $name = $request->name;
        $id = $request->id;
        $doctor = User::where('role', 'doctor')
            ->where('name', 'LIKE', '%' . $name . '%')
            ->where('specialist_id', $id)
            ->with('clinic', 'specialist')
            ->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }

    // get doctor by id
    public function getDoctorByid($id)
    {
        $doctor = User::where('id', $id)->where('role', 'doctor')->with('clinic', 'specialist')->first();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }

    //get clinic by id
    public function getClinicById($id)
    {
        $clinic = Clinic::find($id);
        $clinicName = $clinic->name;
        $clinicImage = $clinic->image;
        $totalDoctor = User::where('clinic_id', $id)->where('role', 'doctor')->count();
        $totalPatient = Order::where('clinic_id', $id)->count();
        $totalIncome = Order::where('clinic_id', $id)->where('status', 'Success')->sum('price');
        return response()->json([
            'status' => 'success',
            'data' => [
                'clinic_name' => $clinicName,
                'clinic_image' => $clinicImage,
                'total_doctor' => $totalDoctor,
                'total_patient' => $totalPatient,
                'total_income' => $totalIncome
            ]
        ]);
    }

    //get doctor by clinic
    public function getDoctorByClinic($clinic_id)
    {
        $doctor = User::where('clinic_id', $clinic_id)->where('role', 'doctor')->with('clinic', 'specialist')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }

    // get doctor by specialist_id
    public function getDoctorBySpecialist($id)
    {
        $doctor = User::where('specialist_id', $id)->where('role', 'doctor')->with('clinic', 'specialist')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }
}

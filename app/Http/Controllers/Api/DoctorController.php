<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    //index
    public function index()
    {
        $doctor = User::where('role', 'doctor')->with('doctor', 'specialization')->get();
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
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
            'clinic_id' => 'required',
            'specialization_id' => 'required|exists:specializations,id'
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
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
            'data' => $doctor
        ], 201);
    }
    //update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
            'clinic_id' => 'required',
            'specialization_id' => 'required|exists:specializations,id'
        ]);
        $data = $request->all();
        $doctor = User::find($id);
        $doctor->update($data);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $filePath = $image->storeAs('doctor',  $imageName, 'public');
            $doctor->image = '/storage/' . $filePath;
            $doctor->save();
        }
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ],);
    }

    //destroy
    public function destroy($id)
    {
        $doctor = User::find($id);
        $doctor->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Doctor deleted successfully'
        ], 200);
    }

    //get doctor active
    public function getActiveDoctor()
    {
        $doctor = User::where('role', 'doctor')->where('status', 'active')->with('doctor', 'specialization')->get();
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
            ->with('doctor', 'specialization')
            ->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }

    // get doctor by id
    public function getDoctorByid($id)
    {
        $doctor = User::where('id', $id)->where('role', 'doctor')->with('doctor', 'specialization')->first();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }

    //get doctor by clinic
    public function getDoctorByClinic($id)
    {
        $doctor = User::where('clinic_id', $id)->where('role', 'doctor')->with('doctor', 'specialization')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }

    // get doctor by specialist_id
    public function getDoctorBySpecialist($id)
    {
        $doctor = User::where('specialist_id', $id)->where('role', 'doctor')->with('doctor', 'specialization')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $doctor
        ], 200);
    }
}

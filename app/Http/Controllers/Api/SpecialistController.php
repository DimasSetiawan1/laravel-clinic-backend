<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    public function index(){
        return response()->json([
            "status"=>"Success",
            "data" => Specialist::all()
        ]);
    }
}

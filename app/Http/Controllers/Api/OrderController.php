<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\XenditSdkException;

class OrderController extends Controller
{
    //index
    public function index()
    {
        $order = Order::with('patient', 'doctor', 'clinic')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $order
        ], 200);
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'service' => 'required',
            'price' => 'required',
            'duration' => 'required',
            'clinic_id' => 'required',
            'schedule' => 'required'
        ]);
        $data = $request->all();
        $order = Order::create($data);

        $key = config('services.xendit.server_key');
        Configuration::setXenditKey($key);

        $apiInstance = new InvoiceApi();
        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => 'INV-' . $order->id,
            'description' => 'Payment for ' . $order->service,
            'amount' => $order->price,
            'invoice_duration' => 172800,
            'currency' => 'IDR',
            'reminder_time' => 1,
            'success_redirect_url' => 'flutter/success',
            'failure_redirect_url' => 'flutter/failure',
        ]);

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);
            $payment_url = $result->getInvoiceUrl();
            $order->payment_url = $payment_url;
            $order->save();

            return response()->json([
                'status' => 'Success',
                'data' => $order
            ], 201);
        } catch (XenditSdkException $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // handle callback xendit
    public function handleXenditCallback(Request $request)
    {
        $xenditCallbackToken = config('services.xendit.callback_token');
        $callbackToken = $request->header('X-CALLBACK-TOKEN');
        if ($callbackToken !== $xenditCallbackToken) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized'
            ], 401);
        }
        $data = $request->all();
        $externalId = $data['external_id'];
        $order = Order::where('id', explode('-', $externalId)[1])->first();
        $order->status = $data['status'];
        $order->save();
        return response()->json([
            'status' => 'Success',
            'data' => $order
        ], 200);
    }


    //get order history by patient desc
    public function getOrderHistoryByPatient($patient_id)
    {
        $order = Order::where('patient_id', $patient_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $order
        ], 200);
    }

    //get order history by doctor desc
    public function getOrderHistoryByDoctor($doctor_id)
    {
        $order = Order::where('doctor_id', $doctor_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $order
        ], 200);
    }

    //get order history by clinic desc
    public function getOrderHistoryByClinic($clinic_id)
    {
        $order = Order::where('clinic_id', $clinic_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $order
        ], 200);
    }

    // get admin clinic summary
    public function getSummary($clinic_id)
    {
        $orders = Order::where('clinic_id', $clinic_id)->with('clinic', 'patient', 'doctor')->get();
        $orderCount = $orders->count();
        $totalIncome = $orders->where('status', 'paid')->sum('price');
        $doctorCount = $orders->groupBy('doctor_id')->count();
        $patientCount = $orders->groupBy('patient_id')->count();
        return response()->json([
            'status' => 'Success',
            'data' => [
                'order_count' => $orderCount,
                'total_income' => $totalIncome,
                'doctor_count' => $doctorCount,
                'patient_count' => $patientCount
            ]
        ], 200);
    }
}

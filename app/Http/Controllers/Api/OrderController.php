<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OneSignal;
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

        try {

            $key = config('services.xendit.server_key');
            Configuration::setXenditKey($key);
            $data = $request->all();
            $order = Order::create($data);
            $apiInstance = new InvoiceApi();
            $create_invoice_request = new CreateInvoiceRequest([
                'external_id' => 'INV-' . $order->id,
                'description' => 'Payment for ' . $order->service,
                'amount' => $order->price,
                'invoice_duration' => 86400,
                'currency' => 'IDR',
                'reminder_time' => 1,
                'success_redirect_url' => 'flutter/success',
                'failure_redirect_url' => 'flutter/failure',
            ]);
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
                'message' => $e->getMessage(),
                'details' => $e->getFullError(),
            ], 400);
        }
    }

    // handle callback xendit
    public function handleXenditCallback(Request $request)
    {
        $xenditCallbackToken = config('services.xendit.callback_token');
        $callbackToken = $request->header('X-Callback-Token');
        if (!hash_equals($xenditCallbackToken, $callbackToken)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized'
            ], 401);
        }
        $data = $request->all();
        $externalId = $data['external_id'];
        $order = Order::where('id', explode('-', $externalId)[1])->first();
        if (empty($order)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Order not found'
            ], 404);
        }
        $doctor = User::find($order->doctor_id);
        $order->status = $data['status'];
        $order->status_service = "ACTIVE";
        $order->save();
        OneSignal::sendNotificationToUser(
            "You Have a New " . $order->service . " from " . $order->patient->name,
            $doctor->one_signal_token,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        return response()->json([
            'status' => 'Success',
            'message' => 'Payment Success'
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
    public function getOrderHistory($clinic_id)
    {
        $order = Order::where('clinic_id', $clinic_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $order
        ], 200);
    }



    public function getOrderByDoctorQuery($doctor_id, $service, $status_service)
    {
        $search = Order::where('doctor_id', $doctor_id)
            ->where('service', $service)
            ->where('status_service', $status_service)
            ->with('doctor', 'clinic', 'patient')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'Success',
            'data' => $search
        ]);
    }
}

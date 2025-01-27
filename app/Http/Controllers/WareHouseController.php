<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWearHouseRequest;
use App\Models\WearHouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WareHouseController extends Controller
{
    public function show()
    {
        return view('users.warehouse.create');
    }

    public function create(CreateWearHouseRequest $request)
    {
        $access_key = env('PARCELX_ACCESS_KEY');
        $secret_key = env('PARCELX_SECRET_KEY');
        $auth_token = base64_encode($access_key . ':' . $secret_key);

        $data = [
            'address_title' => $request->address_title,
            'sender_name' => $request->sender_name,
            'full_address' => $request->full_address,
            'phone' => $request->phone,
            'pincode' => $request->pincode,
        ];

        try {
            $response = Http::withHeaders([
                'access-token' => $auth_token,
            ])
                ->withOptions(['verify' => false])
                ->post('https://app.parcelx.in/api/v1/create_warehouse', $data);

            if ($response->successful()) {
                $responseData = $response->json();

                Log::info('API Response:', $responseData);

                $warehouse = WearHouse::create($request->validated());

                return redirect()->back()->with('success', 'Warehouse created successfully!');
            } else {
                $responseBody = $response->json();
                $errorMessage = $responseBody['responsemsg'] ?? 'Unknown error occurred';
                Log::info('API Error:', ['response' => $responseBody]);

                return redirect()->back()->with('error', $errorMessage);
            }
        } catch (Exception $e) {
            Log::error('Exception:', ['message' => $e->getMessage()]);

            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

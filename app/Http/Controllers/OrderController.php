<?php

namespace App\Http\Controllers;

use App\Helpers\ParcelxHelper;
use App\Http\Requests\CancelOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Show Order Form
     */
    public function show()
    {
        $user = Auth::user();
        $chargeableAmount = $user->chargeable_amount;

        // $totalAmount = Wallet::where('user_id', $user->id)->first();
        // if (!$totalAmount) {
        //     session()->flash('error', 'Insufficient Balance Please Recharge Wallet!!');
        // } elseif ($totalAmount->amount < $chargeableAmount) {
        //     session()->flash('error', 'Insufficient Balance Please Recharge Wallet!!');
        // }

        $data['warehouses'] = Warehouse::where(['status' => 1, 'user_id' => $user->id])->get();
        return view('users.orders.create', $data);
    }

    /**
     * Save Order Data
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $chargeableAmount = $user->chargeable_amount;

        if (!$request->has('product_name') || empty($request->product_name)) {
            session()->flash('error', 'The products field is required.');
            return redirect()->back();
        }

        $apiData = [
            'client_order_id' => $request->order_id,
            'consignee_emailid' => $request->consignee_emailid ?? '',
            'consignee_pincode' => $request->consignee_pincode,
            'consignee_mobile' => $request->consignee_mobile,
            'consignee_phone' => $request->consignee_phone ?? '',
            'consignee_address1' => $request->consignee_address1,
            'consignee_address2' => $request->consignee_address2 ?? '',
            'consignee_name' => $request->consignee_name,
            'invoice_number' => $request->invoice_number,
            'express_type' => 'surface',
            'pick_address_id' => $request->pickup_address,
            'return_address_id' => $request->is_return_address === 'true' ? $request->return_address : $request->pickup_address,
            'cod_amount' => $request->cod_amount ?? '0',
            'tax_amount' => $request->tax_amount ?? '0',
            'mps' => $request->mps ?? '0',
            'courier_type' => 1,
            'courier_code' => 'PXDEL01',
            'address_type' => $request->address_type ?? 'Home',
            'payment_mode' => $request->payment_mode ?? 'Prepaid',
            'order_amount' => $request->order_amount ?? '0.00',
            'extra_charges' => $request->extra_charges ?? '0',
            'shipment_width' => $request->shipment_width ?? ['1'],
            'shipment_height' => $request->shipment_height ?? ['1'],
            'shipment_length' => $request->shipment_length ?? ['1'],
            'shipment_weight' => $request->shipment_weight ?? ['1'],
            'products' => [],
            'awb_number' => null,
            'order_number' => null,
            'job_id' => null,
            'lrnum' => '',
            'waybills_num_json' => null,
            'lable_data' => null,
            'routing_code' => null,
            'partner_display_name' => null,
            'pickup_id' => null,
            'courier_name' => null,
        ];


        if ($request->has('product_name') && is_array($request->product_name)) {
            foreach ($request->product_name as $index => $product_name) {
                $product = [
                    'product_sku' => $request->product_sku[$index] ?? '',
                    'product_name' => $product_name,
                    'product_value' => $request->product_value[$index] ?? '0',
                    'product_hsnsac' => $request->product_hsnsac[$index] ?? '',
                    'product_taxper' => $request->product_taxper[$index] ?? 0,
                    'product_category' => $request->product_category[$index] ?? '',
                    'product_quantity' => $request->product_quantity[$index] ?? '1',
                    'product_description' => $request->product_description[$index] ?? '',
                ];
                $apiData['products'][] = $product;
            }
        }

        Log::info('API Request Data:', $apiData);

        try {
            $url = 'https://app.parcelx.in/api/v3/order/create_order';
            $response = ParcelxHelper::sendRequest($url, $apiData);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('API Response:', $responseData);

                if (!$responseData['status']) {
                    session()->flash('error', $responseData['responsemsg'][0]);
                    return redirect()->back();
                }


                $dbData = $apiData;
                $dbData['shipment_width'] = json_encode($apiData['shipment_width']);
                $dbData['shipment_height'] = json_encode($apiData['shipment_height']);
                $dbData['shipment_length'] = json_encode($apiData['shipment_length']);
                $dbData['shipment_weight'] = json_encode($apiData['shipment_weight']);
                $dbData['products'] = json_encode($apiData['products']);


                $dbData['awb_number'] = $responseData['data']['awb_number'] ?? null;
                $dbData['order_number'] = $responseData['data']['order_number'] ?? null;
                $dbData['job_id'] = $responseData['data']['job_id'] ?? null;
                $dbData['lrnum'] = $responseData['data']['lrnum'] ?? '';
                $dbData['waybills_num_json'] = $responseData['data']['waybills_num_json'] ?? null;
                $dbData['lable_data'] = $responseData['data']['lable_data'] ?? null;
                $dbData['routing_code'] = $responseData['data']['routing_code'] ?? null;
                $dbData['partner_display_name'] = $responseData['data']['partner_display_name'] ?? null;
                $dbData['pickup_id'] = $responseData['data']['pickup_id'] ?? null;
                $dbData['courier_name'] = $responseData['data']['courier_name'] ?? null;
                $dbData['user_id'] = $user->id;
                $dbData['status'] = 221;


                $order = Order::create($dbData);


                foreach ($apiData['products'] as $product) {
                    $product['order_id'] = $order->id;
                    Product::create($product);
                }

                // Deduct wallet charge
                $chargeableAmount;
                // $totalAmount = Wallet::where('user_id', $user->id)->first();
                // $updatedAmount = $totalAmount->amount - $chargeableAmount;

                // $totalAmount->update([
                //     'amount' => $updatedAmount
                // ]);

                $totalAmount = Wallet::where('user_id', $user->id)->first();

                if ($totalAmount) {
                    $updatedAmount = $totalAmount->amount - $chargeableAmount;
                    $totalAmount->update(['amount' => $updatedAmount]);
                } else {
                    Wallet::create([
                        'user_id' => $user->id,
                        'amount' => -$chargeableAmount
                    ]);
                }



                $walletTransactions = WalletTransaction::where([
                    'user_id' => Auth::id(),
                    'status' => 101
                ])->get();

                // update status after add amount or update amount
                foreach ($walletTransactions as $transaction) {
                    $transaction->update(['status' => 102]);
                }
                $user->logActivity($user, 'Order created successfully', 'order_created');

                session()->flash('success', 'Order placed successfully! AWB: ' . $responseData['data']['awb_number']);
                return redirect()->back();
            } else {
                $responseBody = $response->json();
                Log::error('API Error:', ['response' => $responseBody]);

                session()->flash('error', $responseBody['responsemsg'] ?? 'Unknown error occurred');
                return redirect()->back();
            }
        } catch (Exception $e) {
            $user->logActivity($e->getMessage(), 'Exception: Order Creation Failed', 'order_failed');

            Log::error('Exception:', ['message' => $e->getMessage()]);
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * List Order Data
     */
    public function list(Request $request)
    {
        $search = $request->search;

        $baseQuery = Order::where('user_id', Auth::user()->id)
            ->select([
                'id',
                'client_order_id',
                'invoice_number',
                'awb_number',
                'consignee_name',
                'order_amount',
                'payment_mode',
                'status',
                'created_at'
            ]);

        if (!empty($search)) {
            $baseQuery->where('awb_number', 'LIKE', "%{$search}%");
        }

        $bookedQuery = clone $baseQuery;
        $cancelledQuery = clone $baseQuery;
        $allOrdersQuery = clone $baseQuery;

        $data['bookedOrders'] = $bookedQuery->where('status', 221)->paginate(10, ['*'], 'booked_page');
        $data['cancelledOrders'] = $cancelledQuery->where('status', 229)->paginate(10, ['*'], 'cancelled_page');
        $data['allOrders'] = $allOrdersQuery->paginate(10, ['*'], 'all_page');

        return view('users.orders.list', $data);
    }

    /**
     * View Order Data
     */
    public function view($id)
    {
        $order = Order::where(['id' => $id])->with('productsData')->first();
        return view('users.orders.view', compact('order'));
    }

    /**Cancel Order */

    public function cancelOrder(CancelOrderRequest $request)
    {
        $awbNumber = $request->awb_number;
        $order = Order::where('awb_number', $awbNumber)->first();
        $user = Auth::user();

        $url = 'https://app.parcelx.in/api/v1/order/cancel_order';
        $apiData = ['awb' => $awbNumber];

        $response = ParcelxHelper::sendRequest($url, $apiData);
        $responseData = $response->json(); // Get response as an array

        if ($response->successful() && isset($responseData['status']) && $responseData['status'] == true) {
            $user->logActivity($user, 'Order canceled successfully', 'order_canceled');

            $order->update(['status' => '229']);
            return response()->json(['success' => true, 'message' => 'Order canceled successfully']);
        } else {
            $user->logActivity($user(), 'Exception: Order cancel Failed', 'order_failed');

            $errorMsg = $responseData['responsemsg'] ?? 'Failed to cancel order';
            return response()->json(['success' => false, 'message' => $errorMsg], 400);
        }
    }

    /**
     * Order Label Data 
     * */
    public function orderLabelData(CancelOrderRequest $request)
    {
        $awbNumber = $request->awb_number;

        $url = 'https://app.parcelx.in/api/v1/label?awb=' . $awbNumber . '&label_type=label';

        $response = ParcelxHelper::sendGETRequest($url, []);
        $responseData = $response->json();

        if ($response->successful() && isset($responseData['status']) && $responseData['status'] == true) {
            return response()->json(['success' => true, 'message' => 'Order label data get successfully', 'label_url' => $responseData['label_url']]);
        } else {
            $errorMsg = $responseData['responsemsg'] ?? 'Failed to get order label data';
            return response()->json(['success' => false, 'message' => $errorMsg], 400);
        }
    }
}

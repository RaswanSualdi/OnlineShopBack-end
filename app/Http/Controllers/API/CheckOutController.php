<?php

namespace App\Http\Controllers\API;

use App\Mail\notifications;
use App\Models\Product;
use App\Models\Transaction;

use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Http\Requests\API\CheckOutRequest;

use App\Http\Controllers\API\ResponseFormatter;

class CheckOutController extends Controller
{
    public function checkout(CheckOutRequest $request){
        $data = $request->except('transaction_details');
        $data['uuid']= 'TRX'.mt_rand(10000,99999).mt_rand(100,999);

        $transaction = Transaction::create($data);
        Mail::to($transaction->email)->send(new notifications($transaction->name));
       
        foreach ($request->transaction_details as $product)
        {
            $details[] = new TransactionDetail([
                'transactions_id' => $transaction->id,
                'products_id' => $product,
            ]);

            Product::find($product)->decrement('quantity');
        }

        

        $transaction->details()->saveMany($details);
        return ResponseFormatter::success($transaction);
    }
}

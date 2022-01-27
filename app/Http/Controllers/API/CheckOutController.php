<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CheckOutRequest;
use App\Http\Controllers\API\ResponseFormatter;
use App\Notifications\notofications;

class CheckOutController extends Controller
{
    public function checkout(CheckOutRequest $request){
        $data = $request->except('transaction_details');
        $data['uuid']= 'TRX'.mt_rand(10000,99999).mt_rand(100,999);

        $transaction = Transaction::create($data);
        $transaction->notify(new notofications($transaction));
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

<?php

namespace App\Http\Controllers;

use App\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExpensesController extends Controller
{
    public function index(){
        $all_expenses = Expenses::paginate(10);
        return view('Expenses.index',compact('all_expenses'));
    }

    public function store(Request $request){
       $expenses = new Expenses;
//       dd($request->expense);
       $request->vat = (int)$request->vat;
        if(strpos($request->expense, "EUR") !== false){
            $req_url = 'https://api.exchangerate-api.com/v4/latest/EUR';
            $response_json = file_get_contents($req_url);

// Continuing if we got a result
            if(false !== $response_json) {
                // Try/catch for json_decode operation
                try {
                    // Decoding
                    $response_object = json_decode($response_json);

                    // YOUR APPLICATION CODE HERE, e.g.
                    $base_price = (int)$request->expense; // Your price in USD
                    $GBP_price = round(($base_price * $response_object->rates->GBP), 2);
                    $GBP_price = $GBP_price . ' Pounds';
                    $expenses->create([
                        'expense' => $GBP_price,
                        'vat' => $request->vat,
                        'reason' => $request->reason,
                        'date' => $request->date
                    ]);
                    Session::flash('success', 'Expense added Successfully');
                    return redirect()->back();
                }
                catch(Exception $e) {
                    Session::flash('error', 'Something went wrong with the api');
                    return redirect()->back();
                }

            }
        }

       $expenses->create([
           'expense' => $request->expense,
           'vat' => $request->vat,
           'reason' => $request->reason,
           'date' => $request->date
       ]);
        Session::flash('success', 'Expense added Successfully');
        return redirect()->back();
    }


}

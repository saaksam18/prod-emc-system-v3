<?php

namespace App\Http\Controllers\operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DepositModel;
use DB;

class ExchangeDepositController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:operation-list|operation-create|operation-edit|operation-delete', ['only' => ['index','show']]);
         $this->middleware('permission:operation-create', ['only' => ['create','store']]);
         $this->middleware('permission:operation-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:operation-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $exchanges = DB::table('tbl_exchange_rental_deposit')
        ->join('tbl_customer', 'tbl_exchange_rental_deposit.customerID', '=', 'tbl_customer.customerID')
        ->join('users', 'tbl_exchange_rental_deposit.userID', '=', 'users.id')
        ->select('tbl_exchange_rental_deposit.*', 'tbl_customer.CustomerName as CustomerName', 'users.name as name')
        ->paginate(50);

        return view('layouts/sections/operation/rentals/dp-exchanges/index', compact('exchanges'));
    }
}

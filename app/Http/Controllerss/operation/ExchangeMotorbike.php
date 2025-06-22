<?php

namespace App\Http\Controllers\operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\operations\ExchangeMotorModel;
use App\Models\MotorbikeInfo;
use App\Models\operations\CustomerModel;
use App\Models\operations\RentalModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExchangeMotorbike extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:operation-list|operation-create|operation-edit|operation-delete', ['only' => ['index','show']]);
         $this->middleware('permission:operation-create', ['only' => ['create','store']]);
         $this->middleware('permission:operation-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:operation-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $exchangeMotors = DB::table('tbl_exchange_motor')
        ->join('tbl_customer', 'tbl_exchange_motor.customerID', '=', 'tbl_customer.customerID')
        ->join('tbl_motorinfor', 'tbl_exchange_motor.preMotoID', '=', 'tbl_motorinfor.motorID')
        ->join('users', 'tbl_exchange_motor.userID', '=', 'users.id')
        ->select('tbl_exchange_motor.*', 'tbl_customer.CustomerName as CustomerName','tbl_motorinfor.motorno as motorno', 'users.name as name')
        ->orderBy('tbl_exchange_motor.created_at', 'desc')
        ->paginate(50);

        return view('layouts/sections/operation/rentals/exchanges/index', compact('exchangeMotors'));
    }
    
    public function search(Request $request)
    {
        // Get the value of the search input.
        $search = $request->input('search');
    
        // Return the filtered data to the view.
        $exchangeMotors = DB::table('tbl_exchange_motor')
        ->join('tbl_customer', 'tbl_exchange_motor.customerID', '=', 'tbl_customer.customerID')
        ->join('tbl_motorinfor', 'tbl_exchange_motor.preMotoID', '=', 'tbl_motorinfor.motorID')
        ->join('users', 'tbl_exchange_motor.userID', '=', 'users.id')
        ->select('tbl_exchange_motor.*', 'tbl_customer.CustomerName as CustomerName','tbl_motorinfor.motorno as motorno', 'users.name as name')
        ->where('tbl_customer.CustomerName', 'LIKE', "%{$search}%")
        ->orWhere('tbl_exchange_motor.created_at', 'LIKE', "%{$search}%")
        ->paginate(50);

        return view('layouts/sections/operation/rentals/exchanges/index', compact('exchangeMotors'));
    }

}

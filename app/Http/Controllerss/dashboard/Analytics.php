<?php

namespace App\Http\Controllers\dashboard;
use DB;
use Carbon\Carbon;
use DateTime;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\operations\CustomerModel;
use App\Models\operations\RentalModel;
use App\Models\operations\DepositModel;
use App\Models\operations\ContactModel;
use App\Models\operations\VisaModel;
use App\Models\operations\WPModel;
use App\Models\MotorbikeInfo;
use App\Models\User;


class Analytics extends Controller
{
  function __construct()
  {
       $this->middleware('permission:dashboard-list', ['only' => ['index','show']]);
  }
  
  public function index()
  {
      $motorbikes = MotorbikeInfo::all();
      $customers = CustomerModel::all();
      $users = User::all();

        $rentals = RentalModel::with(['customer', 'motorInfor', 'deposit'])
        ->where(function ($query) {
            $query->where('transactionType', '!=', 'Return')
                ->where('tbl_rental.is_Active', 1)
                ->whereDate('returnDate', '<=', now());
            })
            ->orderBy('returnDate')
            ->sortable()
            ->paginate(10);

        foreach ($rentals as $rental) {
            $today = Carbon::now();
            $dueDate = Carbon::parse($rental->returnDate);
            $rental->remainingDays = $dueDate->diffInDays($today);
        }
        
        $rental_deposits = DepositModel::where('is_Active' , 1)->get();
        $customer_contacts = ContactModel::where('is_Active' , 1)->get();

        // visa customers
        $totalCustomers = VisaModel::where('is_Active', 1)
        ->whereDate('remindDate', '<=', now())
        ->count();

        // wp customers
        $totalWPCustomers = WPModel::where('is_Active', 1)
        ->whereDate('wpRemindDate', '<=', now())
        ->count();

        if ($motorbikes != null) {
            $totalInstock = MotorbikeInfo::where(function ($query) {
              $query->where('motorStatus', 1)
                  ->orWhere('motorStatus', 5);
              })
            ->count();
            
            $totalOnRent = MotorbikeInfo::where('motorStatus', 2)
            ->count();
  
            $totalMotors = array_sum([$totalInstock, $totalOnRent]);
          } else {
            $totalInstock = 0;
            $totalOnRent = 0;
            $totalMotors = 0;
          }

    // Deposit
    $rental_dp = RentalModel::where('is_Active', 1)->get();
        if ($rental_dp != null) {
            $countCashs = DepositModel::where(function ($query) {
              $query->where('currDepositType', 'Money')
                    ->where('is_Active', 1);
            })
            ->sum('currDeposit');
      
            $countPPs = DepositModel::where(function ($query) {
              $query->where('currDepositType', 'Passport')
                    ->where('is_Active', 1);
            })
            ->count('currDeposit');
          } else {
            $countCashs = 0;
            $countPPs = 0;
          }
    // End Deposit

    // Yearly Rental Chart
    // Rental by Quarter
    // Last After Year Q1
    $start_date_Q1_firstYear = date('Y-01-01', strtotime('-2 year'));
    $end_date_Q1_firstYear = date('Y-03-31', strtotime('-2 year'));
    $lastAYQ1 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q1_firstYear, $end_date_Q1_firstYear])
        ->count();
    // End After Last Year Q1
    // Last After Year Q2
    $start_date_Q2_firstYear = date('Y-04-01', strtotime('-2 year'));
    $end_date_Q2_firstYear = date('Y-06-30', strtotime('-2 year'));
    $lastAYQ2 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q2_firstYear, $end_date_Q2_firstYear])
        ->count();
    // End After Last Year Q2
    // Last After Year Q3
    $start_date_Q3_firstYear = date('Y-07-01', strtotime('-2 year'));
    $end_date_Q3_firstYear = date('Y-09-30', strtotime('-2 year'));
    $lastAYQ3 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q3_firstYear, $end_date_Q3_firstYear])
        ->count();
    // End After Last Year Q3
    // Last After Year Q4
    $start_date_Q4_firstYear = date('Y-10-01', strtotime('-2 year'));
    $end_date_Q4_firstYear = date('Y-12-31', strtotime('-2 year'));
    $lastAYQ4 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q4_firstYear, $end_date_Q4_firstYear])
        ->count();
    // End After Last Year Q4

    // Last Year Q1
    $start_date_Q1_secYear = date('Y-01-01', strtotime('-1 year'));
    $end_date_Q1_secYear = date('Y-03-31', strtotime('-1 year'));
    $lastYQ1 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q1_secYear, $end_date_Q1_secYear])
        ->count();
    // End Last Year Q1
    // Last Year Q2
    $start_date_Q2_secYear = date('Y-04-01', strtotime('-1 year'));
    $end_date_Q2_secYear = date('Y-06-30', strtotime('-1 year'));
    $lastYQ2 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q2_secYear, $end_date_Q2_secYear])
        ->count();
    // End Last Year Q2
    // Last Year Q3
    $start_date_Q3_secYear = date('Y-07-01', strtotime('-1 year'));
    $end_date_Q3_secYear = date('Y-09-30', strtotime('-1 year'));
    $lastYQ3 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q3_secYear, $end_date_Q3_secYear])
        ->count();
    // End Last Year Q3
    // Last Year Q4
    $start_date_Q4_secYear = date('Y-10-01', strtotime('-1 year'));
    $end_date_Q4_secYear = date('Y-12-31', strtotime('-1 year'));
    $lastYQ4 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q4_secYear, $end_date_Q4_secYear])
        ->count();
    // End Last Year Q4

    // Current Year Q1
    $start_date_Q1_currYear = date('Y-01-01');
    $end_date_Q1_currYear = date('Y-03-31');
    $currYQ1 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q1_currYear, $end_date_Q1_currYear])
        ->count();
    // End Current Year Q1
    // Current Year Q2
    $start_date_Q2_currYear = date('Y-04-01');
    $end_date_Q2_currYear = date('Y-06-30');
    $currYQ2 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q2_currYear, $end_date_Q2_currYear])
        ->count();
    // End Current Year Q2
    // Current Year Q3
    $start_date_Q3_currYear = date('Y-07-01');
    $end_date_Q3_currYear = date('Y-09-30');
    $currYQ3 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q3_currYear, $end_date_Q3_currYear])
        ->count();
    // End Current Year Q3
    // Current Year Q4
    $start_date_Q4_currYear = date('Y-10-01');
    $end_date_Q4_currYear = date('Y-12-31');
    $currYQ4 = DB::table('tbl_rental')
        ->where('transactionType', '=', 'New Rental')
        ->whereBetween('created_at', [$start_date_Q4_currYear, $end_date_Q4_currYear])
        ->count();
    // End Current Year Q4
            
    // End Rental by Quarter
    
    $currentYear = date('Y');
    $lastYear = date('Y', strtotime('-1 year'));
    $lastAfterYear = date('Y', strtotime('-2 year'));
    $quarters = [$lastAfterYear . " Q1", $lastAfterYear . " Q2", $lastAfterYear . " Q3", $lastAfterYear . " Q4", 
                $lastAfterYear . " Q1", $lastYear . " Q2", $lastYear . " Q3", $lastYear . " Q4", 
                $currentYear . " Q1", $currentYear . " Q2", $currentYear . " Q3", $currentYear . " Q4"];

    $rentalAmount = [$lastAYQ1, $lastAYQ2, $lastAYQ3, $lastAYQ4, $lastYQ1, $lastYQ2, $lastYQ3, $lastYQ4, $currYQ1, $currYQ2, $currYQ3, $currYQ4];

    $yearData = [
      'categories' => $quarters,
      'data' => $rentalAmount
  ];

    // End Yearly Rental Chart

        return view('content.dashboard.dashboards-analytics', 
        [
          'rentals' => $rentals, 
          'users' => $users,
          'rental_deposits' => $rental_deposits,
          'motorbikes' => $motorbikes, 
          'customers' => $customers, 
          'customer_contacts' => $customer_contacts,
          'totalCustomers' => $totalCustomers, 
          'totalWPCustomers' => $totalWPCustomers,

          'totalMotors' => $totalMotors,
          'totalInstock' => $totalInstock,
          'totalOnRent' => $totalOnRent,

          'countCashs' => $countCashs,
          'countPPs' => $countPPs,

          // 'data' => $data,
          'yearData' => $yearData
        ]);
  }

}

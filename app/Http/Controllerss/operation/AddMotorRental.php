<?php

namespace App\Http\Controllers\operation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\operations\CustomerModel;
use App\Models\CountriesModel;
use App\Models\operations\DepositModel;
use App\Models\operations\ContactModel;

use App\Models\operations\RentalModel;
use App\Models\operations\RentalLogsModel;
use App\Models\MotorbikeInfo;
use App\Models\User;
use App\Models\operations\ExchangeMotorModel;

use DB;

class AddMotorRental extends Controller
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

    public function index(Request $request)
    {
      $countriesList = CountriesModel::all();
      $motorbikes = MotorbikeInfo::all();
      $customers = CustomerModel::all();
      $users = User::all();

      $customer_contact_type = ContactModel::where('is_Active', 1)->select('contactType')->distinct()->get();
      $customer_contact = ContactModel::where('is_Active', 1)->select('contactDetail')->distinct()->get();
      $customer_deposit_type = DepositModel::where('is_Active', 1)->select('currDepositType')->distinct()->get();
      $customer_deposit = DepositModel::where('is_Active', 1)->select('currDeposit')->distinct()->get();

      $motorbike_model_drop = MotorbikeInfo::where('is_Active', 1)->select('motorModel')->distinct()->get();
      $motorbike_no_drop = MotorbikeInfo::where('is_Active', 1)->select('motorno')->distinct()->get();
      $motorbike_color_drop = MotorbikeInfo::where('is_Active', 1)->select('motorColor')->distinct()->get();

      $rental_price_drop = RentalModel::where('price', '>', 0)->where('is_Active', 1)->select('price')->distinct()->get();
      $rental_tran_drop = RentalModel::where('is_Active', 1)->where('transactionType', '!=', 'Return')->select('transactionType')->distinct()->get();

      $rentals = RentalModel::with(['customer', 'contact', 'deposit', 'motorInfor', 'user'])
        ->when($request->returnDate !== null, function ($q) use ($request) {
          $q->where('returnDate', 'LIKE', '%' . $request->returnDate . '%');
        })
        ->when($request->motorno !== null, function ($q) use ($request) {
            $q->whereHas('motorInfor', function ($query) use ($request) {
              $query->where('motorno', 'LIKE', '%' . $request->motorno . '%');
            });
        })
        ->when($request->motorType !== null, function ($q) use ($request) {
            $q->whereHas('motorInfor', function ($query) use ($request) {
              $query->where('motorType', $request->motorType);
            });
        })
        ->when($request->motorModel !== null, function ($q) use ($request) {
            $q->whereHas('motorInfor', function ($query) use ($request) {
              $query->where('motorModel', 'LIKE', '%' . $request->motorModel . '%');
            });
        })
        ->when($request->CustomerName !== null, function ($q) use ($request) {
            $q->whereHas('customer', function ($query) use ($request) {
              $query->where('CustomerName', 'LIKE', '%' . $request->CustomerName . '%');
            });
        })
        ->when($request->nationality !== null, function ($q) use ($request) {
            $q->whereHas('customer', function ($query) use ($request) {
              $query->where('nationality', 'LIKE', '%' . $request->nationality . '%');
            });
        })
        ->when($request->transactionType !== null, function ($q) use ($request) {
          $q->where('transactionType', $request->transactionType);
        })
        ->when($request->rentalDay !== null, function ($q) use ($request) {
          $q->where('rentalDay', 'LIKE', '%' . $request->rentalDay . '%');
        })
        ->when($request->price !== null, function ($q) use ($request) {
          $q->where('price', 'LIKE', '%' . $request->price . '%');
        })
        ->where(function ($q) use ($request) {
            if ($request->returnDate === null) {
                $q->where('transactionType', '!=', 'Return')
                ->where('tbl_rental.is_Active', 1);
            }
        })
        ->sortable()
        ->paginate(50);
        
      $rental_deposits = DepositModel::where('is_Active' , 1)->get();
      $customer_contacts = ContactModel::where('is_Active' , 1)->get();

        if ($rentals != null) {
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
          
          $countOthers = DepositModel::where(function ($query) {
            $query->where('currDepositType', 'Others')
                  ->where('is_Active', 1);
          })
          ->count('currDeposit');

          $cus_late_payment = RentalModel::with(['customer', 'motorInfor'])
          ->where(function ($query) {
              $query->where('transactionType', '!=', 'Return')
                  ->where('is_Active', 1)
                  ->whereDate('returnDate', '<=', now());
              })
              ->count();
        } else {
          $countCashs = 0;
          $countPPs = 0;
          $countOthers = 0;
          $cus_late_payment = 0;
        }

        $motor = MotorbikeInfo::all();
        if ($motor != null) {
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

        return view('content.rentals.index', 
            [
                'rentals' => $rentals,
                'users' => $users,
                'countCashs' => $countCashs,
                'countPPs' => $countPPs,
                'countOthers' => $countOthers,

                'cus_late_payment' => $cus_late_payment,

                'totalInstock' => $totalInstock,
                'totalOnRent' => $totalOnRent,
                'totalMotors' => $totalMotors,

                'countriesList' => $countriesList,
                'motorbikes' => $motorbikes,
                'customers' => $customers,
                'customer_contact_type' => $customer_contact_type,
                'customer_contact' => $customer_contact,
                'customer_deposit_type' => $customer_deposit_type,
                'customer_deposit' => $customer_deposit,
                'customer_contacts' => $customer_contacts,
                
                'motorbike_model_drop' => $motorbike_model_drop,
                'motorbike_no_drop' => $motorbike_no_drop,
                'motorbike_color_drop' => $motorbike_color_drop,
                'rental_tran_drop' => $rental_tran_drop,
                'rental_deposits' => $rental_deposits,
                'rental_price_drop' => $rental_price_drop
            ]
        );
    }

    public function show(Request $request ,$id)
    {

      // Filter Area
      $countriesList = CountriesModel::all();
      $motorbikes = MotorbikeInfo::all();
      $customers = CustomerModel::all();
      $motorbike_model_drop = MotorbikeInfo::select('motorModel')->distinct()->get();
      $customer_contact_type = ContactModel::select('contactType')->distinct()->get();
      $customer_contact = ContactModel::select('contactDetail')->distinct()->get();
      $customer_deposit_type = DepositModel::select('currDepositType')->distinct()->get();
      $customer_deposit = DepositModel::select('currDeposit')->distinct()->get();
      $motorbike_no_drop = MotorbikeInfo::where('motorStatus', '=', 2)
      ->select('motorno')->distinct()->get();
      $motorbike_color_drop = MotorbikeInfo::select('motorColor')->distinct()->get();
      $rental_price_drop = RentalModel::where('rentalID', $id)->where('price', '<>', 0)->select('price')->distinct()->get();
      $rental_tran_drop = RentalModel::select('transactionType')->distinct()->get();
      // End Filter Area

      $rentals = RentalModel::with(['customer','contact', 'deposit', 'motorInfor', 'user'])
      ->where('rentalID', $id)
      ->get();

      // Get Customer ID from Rental ID
      foreach ($rentals as $rental) {
        $customerID = $rental->customerID;
      }
      // Get contact of customerID
      $contacts = ContactModel::where('customerID', $customerID)->where('is_Active', 1)->get();
      // Get deposit of customerID
      $deposits = DepositModel::where('customerID', $customerID)->get();

      $rental_cusID = RentalModel::where('customerID', $id)
      ->value('customerID');

      $customer_logs = CustomerModel::where('customerID', $rental_cusID)
      ->value('CustomerName');

      $userID = RentalModel::with('user')
      ->where('rentalID', $id)
      ->where('is_Active', 1)
      ->orderBy('rentalID', 'desc')
      ->limit(1)
      ->value('staff_id');

      $staff_name = User::where('id', $userID)->value('name');
      
      $rental_logs = RentalModel::with(['customer', 'contact', 'deposit', 'motorInfor', 'user'])
        ->when($request->returnDate !== null, function ($q) use ($request) {
            $q->where('returnDate', $request->returnDate);
        })
        ->when($request->motorno !== null, function ($q) use ($request) {
            $q->whereHas('motorInfor', function ($query) use ($request) {
              $query->where('motorno', 'LIKE', '%' . $request->motorno . '%');
            });
        })
        ->when($request->transactionType !== null, function ($q) use ($request) {
          $q->where('transactionType', $request->transactionType);
        })
        ->when($request->rentalDay !== null, function ($q) use ($request) {
          $q->where('rentalDay', 'LIKE', '%' . $request->rentalDay . '%');
        })
        ->when($request->returnDate !== null, function ($q) use ($request) {
          $q->where('returnDate', 'LIKE', '%' . $request->returnDate . '%');
        })
        ->when($request->price !== null, function ($q) use ($request) {
          $q->where('price', 'LIKE', '%' . $request->price . '%');
        })
        ->where(function ($q) use ($customerID) {
            if ($customerID != null) {
                $q->where('customerID', $customerID);
            }
        })
        ->sortable()
        ->paginate(50);

      $rental_deposits = DepositModel::all();

      $rental_logs_newRental = RentalModel::where('rentalID', $id)
      ->where('transactionType', 'New Rental')
      ->count();

      return view('content.rentals.show', 
      [
        'rentals' => $rentals,
        'staff_name' => $staff_name,
        'rental_logs' => $rental_logs,
        'rental_deposits' => $rental_deposits,

        'rental_logs_newRental' => $rental_logs_newRental,
        'countriesList' => $countriesList,
        'motorbikes' => $motorbikes,
        'customers' => $customers,
        'contacts' => $contacts,
        'deposits' => $deposits,
        'customer_contact_type' => $customer_contact_type,
        'customer_contact' => $customer_contact,
        'customer_deposit_type' => $customer_deposit_type,
        'customer_deposit' => $customer_deposit,
        
        'motorbike_model_drop' => $motorbike_model_drop,
        'motorbike_no_drop' => $motorbike_no_drop,
        'motorbike_color_drop' => $motorbike_color_drop,
        'rental_tran_drop' => $rental_tran_drop,
        'rental_price_drop' => $rental_price_drop
      ]);
    }

    public function create()
    {
        $users = User::all();
        $customers = CustomerModel::all()->sortByDesc('customerID');
        $motorbikes = MotorbikeInfo::where('motorStatus', 1)->orderBy('motorno', 'asc')->get();
        $countriesList = CountriesModel::all();

        return view('content.rentals.create', compact('customers', 'motorbikes', 'countriesList', 'users'));
    }

    public function store(Request $request)
    {
        $request-> validate([
          'CustomerName' => 'required',
          'motorbikeNo' => 'required',
          'rentalDay' => 'required',
          'returnDate' => 'required',
          'rentalPeriod' => 'required',
          'staffId' => 'required',
          'price' => 'required',

          'inputs.*.currDepositType' => 'required',
          'inputs.*.currDeposit' => 'required'
      ],
      [
        'CustomerName' => 'Customer Name is missing',
        'motorbikeNo' => 'Motorbike No. is missing',
        'rentalDay' => 'Rental Date is missing',
        'returnDate' => 'Return Date is missing',
        'rentalPeriod' => 'Rental Perios is missing',
        'staffId' => 'Incharge Staff is missing',
        'price' => 'Price is missing',
        'inputs.*.currDepositType' => 'Deposit Type is missing',
        'inputs.*.currDeposit' => 'Deposit is missing'

        ]);

        try {
        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');
        
        // Saving to rental
        $CustomerName = request('CustomerName');
        $customerID = DB::table('tbl_customer')->where('CustomerName', $CustomerName)->value('customerID');

        $motorno = request('motorbikeNo');
        $motorID = DB::table('tbl_motorInfor')->where('motorno', $motorno)->value('motorID');

        $rental = new RentalModel;
        $rental->customerID = $customerID;
        $rental->motorID = $motorID;
        $rental->transactionType = "New Rental";
        $rental->rentalDay = $request->input('rentalDay');
        $rental->returnDate = $request->input('returnDate');
        $rental->commingDate = $request->input('commingDate');
        $rental->rentalPeriod = $request->input('rentalPeriod');
        $rental->price = $request->input('price');
        $rental->is_Active = 1;
        $rental->staff_id = $request->input('staffId');
        $rental->userID = auth()->user()->id;
        $rental->created_at = $cDate;
        $rental->updated_at = $cDate;
        $rental->save();
        // End Saving to rental

        // Deposit
        $rentalID = RentalModel::where('customerID', $customerID)
        ->orderBy('rentalID', 'desc')
        ->limit(1)
        ->value('rentalID');
        
        // Change old Depsit is_Active to inA
        /* $deposit_rentalIDs = DepositModel::where('customerID', $customerID)
        ->where('is_Active', 1)
        ->orderBy('depositID', 'desc')
        ->update([
          'is_Active' => 0,
          'userID' => auth()->user()->id,
          'updated_at' => $cDate,
        ]); */
        
        $deposits = [];
          foreach ($request->inputs as $value) {
            $deposits[] = [
              'customerID' => $customerID,
              'rentalID' => $rentalID,
              'currDepositType' => $value['currDepositType'],
              'currDeposit' => $value['currDeposit'],
              'staff_id' => $request->input('staffId'),
              'userID' => auth()->user()->id,
              'created_at' => $cDate,
              'updated_at' => $cDate,
            ];
          }
          DepositModel::insert($deposits);
        // End Deposit

        // Change motor status
        $motorbike = MotorbikeInfo::find($rental->motorID);
        $motorbike->motorStatus = '2';
        $motorbike->customerID = $customerID;
        $motorbike->updated_at = $cDate;
        $motorbike->save();
        // End change motor status
        
          return redirect()->back()->with('success', 'Rental Transaction Successfully Added!');
        } catch (Illuminate\Database\QueryException $e) {
          return redirect()->back()->with('error', 'Rental Transaction Failed!');
        }
    }

    public function edit($id)
    {
        $users = User::all();
        $rental = RentalModel::findOrFail($id);
        $motorbikes = MotorbikeInfo::all();
        $customers = CustomerModel::all();

        return view('content.rentals.edit', compact('rental', 'motorbikes', 'customers', 'users'));
    }

    public function update(Request $request, $id)
    {
      $request-> validate([
        'customerID' => 'required',
        'motorID' => 'required',
        'transactionType' => 'required',
        'rentalDay' => 'required',
        'returnDate' => 'required',
        'rentalPeriod' => 'required',
        'staffId' => 'required',
        'price' => 'required',
      ],
      [
        'customerID' => 'Customer Name is missing',
        'motorID' => 'Motorbike No. is missing',
        'transactionType' => 'Transaction is missing',
        'rentalDay' => 'Rental Date is missing',
        'returnDate' => 'Return Date is missing',
        'rentalPeriod' => 'Rental Period is missing',
        'staffId' => 'Incharge Staff is missing',
        'price' => 'Price is missing',
      ]);
        try{
          $cDate = Carbon::now()->addHours(7);
          $formattedNowRent = $cDate->format('Y-m-d H:i:s');

          $customerID = $request->input('customerID');

          $old_rentalID = RentalModel::where('customerID', $customerID)
          ->orderBy('rentalID', 'desc')
          ->limit(1)
          ->value('rentalID');

          // Change old rental is_Active to inA
          // inA = is not Active
          $rental_inA = RentalModel::findOrFail($id);
          $rental_inA->is_Active = 0;
          $rental_inA->userID = auth()->user()->id;
          $rental_inA->updated_at = $cDate;
          $rental_inA->save();
          // End Change old rental is_Active to inA

          // Saving to rental
          $rental = new RentalModel;
          $rental->customerID = $customerID;
          $rental->motorID = $request->input('motorID');
          $rental->transactionType = $request->input('transactionType');
          if ($request->input('transactionType') == 5) {
            $rental->rentalDay = $rental_inA->rentalDay;
            $rental->returnDate = $rental_inA->returnDate;
            $rental->commingDate = $rental_inA->commingDate;
            $rental->rentalPeriod = $rental_inA->rentalPeriod;
            $rental->price = $rental_inA->price;
          } else {
            $rental->rentalDay = $request->input('rentalDay');
            $rental->returnDate = $request->input('returnDate');
            $rental->rentalPeriod = $request->input('rentalPeriod');
            $rental->commingDate = $request->input('commingDate');
            $rental->price = $request->input('price');
          }
          $rental->staff_id = $request->input('staffId');
          $rental->is_Active = 1;
          $rental->userID = auth()->user()->id;
          $rental->created_at = $cDate;
          $rental->updated_at = $cDate;
          $rental->save();
          // End Saving to rental

          // Deposit
          $new_deposit_rentalIDs = DepositModel::where('rentalID', $old_rentalID)
          ->where('is_Active', 1)
          ->orderBy('depositID', 'desc')
          ->pluck('depositID');
          // Change old Depsit is_Active to inA
          $deposit_rentalIDs = DepositModel::where('rentalID', $old_rentalID)
          ->where('is_Active', 1)
          ->orderBy('depositID', 'desc')
          ->update([
            'is_Active' => 0,
            'userID' => auth()->user()->id,
            'updated_at' => $cDate,
          ]);

          foreach ($new_deposit_rentalIDs as $new_deposit_rentalID) {
            $rentalID = RentalModel::where('customerID', $customerID)
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->value('rentalID');
  
            $old_depositType = DepositModel::where('depositID', $new_deposit_rentalID)->value('currDepositType');
            $old_deposit = DepositModel::where('depositID', $new_deposit_rentalID)->value('currDeposit');
  
            $deposit = new DepositModel;
            $deposit->customerID = $customerID;
            $deposit->rentalID = $rentalID;
            $deposit->currDepositType = $old_depositType;
            $deposit->currDeposit = $old_deposit;
            $deposit->staff_id = $request->input('staffId');
            $deposit->userID = auth()->user()->id;
            $deposit->created_at = $cDate;
            $deposit->updated_at = $cDate;
            if ($request->input('transactionType') == "Return") {
              $deposit->is_Active = 0;
            } else {
              $deposit->is_Active = 1;
            }
            $deposit->save();
          }
          // End Deposit
          
          // Change motor status
          if ($rental->transactionType == 5) {
            $motorbike = MotorbikeInfo::find($rental->motorID);
            $motorbike->motorStatus = 5;
            $motorbike->updated_at = $cDate;
            $motorbike->save();
          } elseif ($rental->transactionType == 'Return'){
            $motorbike = MotorbikeInfo::find($rental->motorID);
            $motorbike->customerID = null;
            $motorbike->motorStatus = 1;
            $motorbike->is_Active = 0;
            $motorbike->updated_at = $cDate;
            $motorbike->save();
          } elseif ($rental->transactionType == 3){
            $motorbike = MotorbikeInfo::find($rental->motorID);
            $motorbike->motorStatus = 3;
            $motorbike->is_Active = 0;
            $motorbike->updated_at = $cDate;
            $motorbike->save();
          } elseif ($rental->transactionType == 4){
            $motorbike = MotorbikeInfo::find($rental->motorID);
            $motorbike->motorStatus = 4;
            $motorbike->is_Active = 0;
            $motorbike->updated_at = $cDate;
            $motorbike->save();
          }
          // End change motor status

        return redirect()->route('rentals.index')->with('success', 'Rental contract has been updated.');
        } catch (Illuminate\Database\QueryException $e) {
          return redirect()->back()->with('error', 'Rental contract update failed.');
        }
    }

    public function destroy($id)
    {
      return view('content.pages-misc-error');
    }

    public function addComingDate($id) 
    {
      $users = User::all();
      $rental = RentalModel::findOrFail($id);
      $motorbikes = MotorbikeInfo::all();
      $customers = CustomerModel::all();

      return view('content.rentals.add-coming-date', compact('rental', 'motorbikes', 'customers', 'users'));
    }
    
    public function updateComningDate(Request $request, $id)
    {
      $request-> validate([
        'customerID' => 'required',
        'motorID' => 'required',
      ],
      [
        'customerID' => 'Customer Name is missing',
        'motorID' => 'Motorbike No. is missing',
      ]);
        try{
          $cDate = Carbon::now()->addHours(7);
          $formattedNowRent = $cDate->format('Y-m-d H:i:s');

          $customerID = $request->input('customerID');

          $old_rentalID = RentalModel::where('customerID', $customerID)
          ->orderBy('rentalID', 'desc')
          ->limit(1)
          ->value('rentalID');

          // Change old rental is_Active to inA
          // inA = is not Active
          $rental_inA = RentalModel::findOrFail($id);
          $rental_inA->is_Active = 0;
          $rental_inA->userID = auth()->user()->id;
          $rental_inA->updated_at = $cDate;
          $rental_inA->save();
          // End Change old rental is_Active to inA

          // Saving to rental
          $rental = new RentalModel;
          $rental->customerID = $customerID;
          $rental->motorID = $request->input('motorID');
          $rental->transactionType = "Add Coming Date";
          $rental->commingDate = $request->input('commingDate');
          $rental->staff_id = $rental_inA->staff_id;
          $rental->rentalDay = $rental_inA->rentalDay;
          $rental->returnDate = $rental_inA->returnDate;
          $rental->rentalPeriod = $rental_inA->rentalPeriod;
          $rental->price = $rental_inA->price;
          $rental->is_Active = 1;
          $rental->userID = auth()->user()->id;
          $rental->created_at = $cDate;
          $rental->updated_at = $cDate;
          $rental->save();
          // End Saving to rental

          // Deposit
          $new_deposit_rentalIDs = DepositModel::where('rentalID', $old_rentalID)
          ->where('is_Active', 1)
          ->orderBy('depositID', 'desc')
          ->pluck('depositID');
          // Change old Depsit is_Active to inA
          $deposit_rentalIDs = DepositModel::where('rentalID', $old_rentalID)
          ->where('is_Active', 1)
          ->orderBy('depositID', 'desc')
          ->update([
            'is_Active' => 0,
            'userID' => auth()->user()->id,
            'updated_at' => $cDate,
          ]);

          foreach ($new_deposit_rentalIDs as $new_deposit_rentalID) {
            $rentalID = RentalModel::where('customerID', $customerID)
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->value('rentalID');
  
            $old_depositType = DepositModel::where('depositID', $new_deposit_rentalID)->value('currDepositType');
            $old_deposit = DepositModel::where('depositID', $new_deposit_rentalID)->value('currDeposit');
  
            $deposit = new DepositModel;
            $deposit->customerID = $customerID;
            $deposit->rentalID = $rentalID;
            $deposit->currDepositType = $old_depositType;
            $deposit->currDeposit = $old_deposit;
            $deposit->staff_id = $request->input('staffId');
            $deposit->userID = auth()->user()->id;
            $deposit->created_at = $cDate;
            $deposit->updated_at = $cDate;
            $deposit->save();
          }
          // End Deposit

        return redirect()->route('home')->with('success', 'Added coming date successfully');
        } catch (Illuminate\Database\QueryException $e) {
          return redirect()->back()->with('error', 'Add coming date failed.');
        }
    }
    
    public function exchangeMotorIndex(Request $request)
    {
      if ($request->preMotorID != null) {
        $preMotoIDs = MotorbikeInfo::where('motorno', $request->preMotorID)->value('motorID');
      } else {
        $preMotoIDs = null;
      }
      
      if ($request->currMotorID != null) {
        $currMotorIDs = MotorbikeInfo::where('motorno', $request->currMotorID)->value('motorID');
      } else {
        $currMotorIDs = null;
      }

      $exchanges = ExchangeMotorModel::with('customer', 'user')
      ->when($request->CustomerName !== null, function ($q) use ($request) {
          $q->whereHas('customer', function ($query) use ($request) {
            $query->where('CustomerName', 'LIKE', '%' . $request->CustomerName . '%');
          });
      })
      ->when($request->preMotorID !== null, function ($q) use ($preMotoIDs) {
          $q->where('preMotoID', 'LIKE', '%' . $preMotoIDs . '%');
      })
      ->when($request->currMotorID !== null, function ($q) use ($currMotorIDs) {
          $q->where('currMotorID', 'LIKE', '%' . $currMotorIDs . '%');
      })
      ->when($request->changeDate !== null, function ($q) use ($request) {
          $q->whereDate('created_at', 'LIKE', '%' . $request->changeDate . '%');
      })
      ->sortable()
      ->paginate(50);

      $customers = CustomerModel::all();
      $motorbikes = MotorbikeInfo::all();
      $users = User::all();

      return view('content.rentals.exchanges.index', compact('exchanges','customers', 'motorbikes', 'users'));
    }

    public function changeMotorEdit($id)
    {
        $rental = RentalModel::findOrFail($id);
        $motorbikes = MotorbikeInfo::all();
        $customers = CustomerModel::all();
        $users = User::all();

        return view('content.rentals.exchanges.edit', compact('rental', 'motorbikes', 'customers', 'users'));
    }

    public function exchangeMotor(Request $request, $id)
    {
      $request-> validate([
        'motorID' => 'required',
        'comment' => 'required',
        'staffId' => 'required',
      ]);
      
      $cDate = Carbon::now()->addHours(7);
      $formattedNowRent = $cDate->format('Y-m-d H:i:s');

      try{
      // Change old rental is_Active to inA
      // inA = is not Active
      $rental_inA = RentalModel::findOrFail($id);
      $rental_inA->is_Active = 0;
      $rental_inA->userID = auth()->user()->id;
      $rental_inA->updated_at = $cDate;
      $rental_inA->save();
      // End Change old rental is_Active to inA

        // Saving to rental
        $rental = new RentalModel;
        $rental->customerID = $rental_inA->customerID;
        $rental->rentalDay = $rental_inA->rentalDay;
        $rental->returnDate = $rental_inA->returnDate;
        $rental->commingDate = $rental_inA->commingDate;
        $rental->rentalPeriod = $rental_inA->rentalPeriod;
        $rental->price = $rental_inA->price;
        $rental->is_Active = 1;
        $rental->userID = auth()->user()->id;
        $rental->created_at = $cDate;
        $rental->updated_at = $cDate;
        $rental->motorID = $request->input('motorID');
        $rental->transactionType = "Exchange";
        $rental->staff_id = $request->input('staffId');
        $rental->save();
        // End Saving to rental

        //Change old motorbike motorStatus to 1
        $oldMotorbike = MotorbikeInfo::findOrFail($rental_inA->motorID);
        $oldMotorbike->motorStatus = 1;
        $oldMotorbike->customerID = null;
        $rental->updated_at = $cDate;
        $oldMotorbike->save();
        //End Change old motorbike motorStatus to 1
        
        //Change new motorbike motorStatus to 2
        $newMotorbikeID = $request->input('motorID');
        $newMotorbike = MotorbikeInfo::findOrFail($newMotorbikeID);
        $newMotorbike->motorStatus = 2;
        $newMotorbike->customerID = $rental_inA->customerID;
        $newMotorbike->updated_at = $cDate;
        $newMotorbike->save();
        //End Change new motorbike motorStatus to 2
        
          // Deposit
          $old_rentalCusID = $rental_inA->customerID;

          $new_deposit_rentalIDs = DepositModel::where('customerID', $old_rentalCusID)
          ->where('is_Active', 1)
          ->orderBy('depositID', 'desc')
          ->pluck('depositID');

          // Change old Depsit is_Active to inA
          $deposit_rentalIDs = DepositModel::where('customerID', $old_rentalCusID)
          ->where('is_Active', 1)
          ->orderBy('depositID', 'desc')
          ->update([
            'is_Active' => 0,
            'userID' => auth()->user()->id,
            'updated_at' => $cDate,
          ]);
          
          foreach ($new_deposit_rentalIDs as $new_deposit_rentalID) {
  
            $old_depositType = DepositModel::where('depositID', $new_deposit_rentalID)->value('currDepositType');
            $old_deposit = DepositModel::where('depositID', $new_deposit_rentalID)->value('currDeposit');
  
            $oldRentalCusID = $rental_inA->customerID;
            $rentalID = RentalModel::where('customerID', $oldRentalCusID)
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->value('rentalID');

            $deposit = new DepositModel;
            $deposit->customerID = $rental_inA->customerID;
            $deposit->rentalID = $rentalID;
            $deposit->currDepositType = $old_depositType;
            $deposit->currDeposit = $old_deposit;
            $deposit->created_at = $cDate;
            $deposit->updated_at = $cDate;
            $deposit->staff_id = $request->input('staffId');
            $deposit->userID = auth()->user()->id;
            $deposit->save();
            // End Deposit
          }

      // Save to Exchange Table
      $exchange_motor = new ExchangeMotorModel;
      $exchange_motor->customerID = $rental_inA->customerID;
      $exchange_motor->preMotoID = $oldMotorbike->motorID;
      $exchange_motor->currMotorID = $request->input('motorID');
      $exchange_motor->comment = $request->input('comment');
      $exchange_motor->staff_id = $request->input('staffId');
      $exchange_motor->userID = auth()->user()->id;
      $exchange_motor->created_at = $cDate;
      $exchange_motor->updated_at = $cDate;
      $exchange_motor->save();
      // End Save to Exchange Table

      return redirect()->route('rentals.index')->with('success', 'Exchange motorbike successfully.');
        } catch (Illuminate\Database\QueryException $e) {
          return redirect()->route('rentals.index')->with('error', 'Exchange motorbike failed.');
        }
    }

    public function exchangeDepositIndex(Request $request) 
    {
      $pre_deposit_type = DepositModel::select('currDepositType')->distinct()->get();
      $pre_deposit = DepositModel::select('currDeposit')->distinct()->get();
      $deposit_type = DepositModel::select('currDepositType')->distinct()->get();
      $deposit = DepositModel::select('currDeposit')->distinct()->get();

      $dps = DepositModel::with(['customer', 'user'])
      ->when($request->CustomerName !== null, function ($q) use ($request) {
          $q->whereHas('customer', function ($query) use ($request) {
            $query->where('CustomerName', $request->CustomerName);
          });
      })
      ->when($request->preDepositType !== null, function ($q) use ($request) {
          $q->where('preDepositType', $request->preDepositType);
      })
      ->when($request->preDeposit !== null, function ($q) use ($request) {
          $q->where('preDeposit', $request->preDeposit);
      })
      ->when($request->depositType !== null, function ($q) use ($request) {
          $q->where('currDepositType', $request->depositType);
      })
      ->when($request->deposit !== null, function ($q) use ($request) {
          $q->where('currDeposit', $request->deposit);
      })
      ->when($request->changeDate !== null, function ($q) use ($request) {
          $q->whereDate('created_at', $request->changeDate);
      })
      ->where(function ($q) {
              $q->where('is_Active', 1);
      })
      ->sortable()
      ->paginate(50);

      $pre_deposits = DepositModel::where('is_Active', 0)->get();
      $curr_deposits = DepositModel::where('is_Active', 1)->get();
      $customers = CustomerModel::all();
      $users = User::all();

      return view('content.rentals.dp-exchanges.index', 
      [
        'pre_deposit_type' => $pre_deposit_type,
        'pre_deposit' => $pre_deposit,
        'deposit_type' => $deposit_type,
        'deposit' => $deposit,

        'pre_deposits' => $pre_deposits,
        'curr_deposits' => $curr_deposits,
        'dps' => $dps,
        'customers' =>$customers,
        'users' => $users
      ]);
    }

    public function changeDepositEdit($id)
    {
        $rental = RentalModel::findOrFail($id);
        $customers = CustomerModel::all();
        $users = User::all();
        
        $pre_deposits = DepositModel::where('customerID', $rental->customerID)
        ->where('is_Active', 1)
        ->get();

        return view('content.rentals.dp-exchanges.edit',compact('rental', 'customers', 'users', 'pre_deposits'));
    }

    public function exchangeDeposit(Request $request, $id)
    {
      $request-> validate([
        'inputs.*.currDepositType' => 'required',
        'inputs.*.currDeposit' => 'required',
        'comment' => 'required'
      ],
      [
        'inputs.*.currDepositType' => 'Deposit Type is missing',
        'inputs.*.currDeposit' => 'Deposit is missing',
        'comment' => 'Comment is missing'
      ]);
        
      try{
        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');

        // Change old rental is_Active to inA
        // inA = is not Active
        $rental_inA = RentalModel::findOrFail($id);
        $rental_inA->is_Active = 0;
        $rental_inA->userID = auth()->user()->id;
        $rental_inA->updated_at = $cDate;
        $rental_inA->save();
        // End Change old rental is_Active to inA

        // Saving to rental
        $rental = new RentalModel;
        $rental->customerID = $rental_inA->customerID;
        $rental->motorID = $rental_inA->motorID;
        $rental->transactionType = "EX. Deposit";
        $rental->rentalDay = $rental_inA->rentalDay;
        $rental->returnDate = $rental_inA->returnDate;
        $rental->rentalPeriod = $rental_inA->rentalPeriod;
        $rental->commingDate = $rental_inA->commingDate;
        $rental->price = $rental_inA->price;
        $rental->staff_id = $request->input('staffId');
        $rental->is_Active = 1;
        $rental->userID = auth()->user()->id;
        $rental->created_at = $cDate;
        $rental->updated_at = $cDate;
        $rental->save();
        // End Saving to rental

        // Deposit
        // Change old Depsit is_Active to inA
        $deposit_rentalIDs = DepositModel::where('rentalID', $id)
        ->where('is_Active', 1)
        ->orderBy('depositID', 'desc')
        ->update([
          'is_Active' => 0,
          'userID' => auth()->user()->id,
          'updated_at' => $cDate,
        ]);

        $customerID = RentalModel::where('rentalID', $id)
        ->value('customerID');
        
        $rentalID = RentalModel::where('customerID', $customerID)
        ->orderBy('rentalID', 'desc')
        ->limit(1)
        ->value('rentalID');

        $deposits = [];
          foreach ($request->inputs as $value) {
            $deposits[] = [
              'customerID' => $customerID,
              'rentalID' => $rentalID,
              'currDepositType' => $value['currDepositType'],
              'currDeposit' => $value['currDeposit'],
              'staff_id' => $request->input('staffId'),
              'userID' => auth()->user()->id,
              'created_at' => $cDate,
              'updated_at' => $cDate,
            ];
          }
          DepositModel::insert($deposits);
        // End Deposit

        return redirect()->route('rentals.index')->with('success', 'Exchanged Deposit Successfully.');
      } catch (Illuminate\Database\QueryException $e) {
        return redirect()->route('rentals.index')->with('error', 'Exchanged Deposit Failed.');
      }

    }

    public function overdue()
    {
      $motorbikes = MotorbikeInfo::all();
      $customers = CustomerModel::all();
      $users = User::all();

        $rentals = RentalModel::with(['customer', 'motorInfor', 'deposit'])
        ->where(function ($query) {
            $query->where('transactionType', '!=', 'Return')
                ->where('is_Active', 1)
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

        return view('content.reports.rentals.overdue', 
        [
          'rentals' => $rentals,
          'users' => $users,
          'rental_deposits' => $rental_deposits,
          'customer_contacts' => $customer_contacts
        ]);
    }

    public function dailyRental(Request $request)
    {
        $rentals = RentalModel::with('customer', 'motorInfor')
        ->when($request->created_at != null, function ($q) use ($request) {
          return $q->whereDate('created_at', $request->created_at);
        }, function ($q) {
          return $q->orWhere(function ($query) {
            $query->whereDate('created_at', now());
          });
        })
        ->get();
        
      $rental_deposits = DepositModel::all();
      $customer_contacts = ContactModel::where('is_Active' , 1)->get();

        /* daily rental transaction */
        $newRentals = RentalModel::where('transactionType', 'New Rental')
        ->when($request->created_at != null, function ($q) use ($request) {
          return $q->whereDate('created_at', $request->created_at);
        }, function ($q) {
          return $q->where(function ($query) {
            $query->whereDate('created_at', now());
          });
        })
        ->count();

        $extensions = RentalModel::where('transactionType', 'Extension')
        ->when($request->created_at != null, function ($q) use ($request) {
          return $q->whereDate('created_at', $request->created_at);
        }, function ($q) {
          return $q->where(function ($query) {
            $query->whereDate('created_at', now());
          });
        })
        ->count();

        $returns = RentalModel::where('transactionType', 'Return')
        ->when($request->created_at != null, function ($q) use ($request) {
          return $q->whereDate('created_at', $request->created_at);
        }, function ($q) {
          return $q->where(function ($query) {
            $query->whereDate('created_at', now());
          });
        })
        ->count();

        $exchangeMotors = RentalModel::where('transactionType', 'Exchange')
        ->when($request->created_at != null, function ($q) use ($request) {
          return $q->whereDate('created_at', $request->created_at);
        }, function ($q) {
          return $q->where(function ($query) {
            $query->whereDate('created_at', now());
          });
        })
        ->count();
        
        $exchangeDeposits = RentalModel::where('transactionType', 'EX. Deposit')
        ->when($request->created_at != null, function ($q) use ($request) {
          return $q->whereDate('created_at', $request->created_at);
        }, function ($q) {
          return $q->where(function ($query) {
            $query->whereDate('created_at', now());
          });
        })
        ->count();

        $totals = array_sum([$newRentals, $extensions, $returns, $exchangeMotors, $exchangeDeposits]);
        /* end daily rental transaction*/

        /* rental percentage */
        // bigAT
        $bigATis = MotorbikeInfo::where('motorType', 1)
        ->where('motorStatus', '=', 1)
        ->count();
        $tempBigATis = MotorbikeInfo::where('motorType', 1)
        ->where('motorStatus', '=', 5)
        ->count();
        $bigATor = MotorbikeInfo::where('motorType', 1)
        ->where('motorStatus', '=', 2)
        ->count();
        $bigATs = array_sum([$bigATis, $tempBigATis, $bigATor]);
        if ($bigATs == 0) {
          $bigATPercentage = 0;
        } else {
          $bigATPercentage = ($bigATor / $bigATs) * 100;
        }
        $totalBigATPercentageFormatted = number_format($bigATPercentage, 2);
        // end bigAT
        
        // AT
        $atis = MotorbikeInfo::where('motorType', 2)
        ->where('motorStatus', '=', 1)
        ->count();
        $tempATis = MotorbikeInfo::where('motorType', 2)
        ->where('motorStatus', '=', 5)
        ->count();
        $ator = MotorbikeInfo::where('motorType', 2)
        ->where('motorStatus', '=', 2)
        ->count();
        
        $ats = array_sum([$atis, $tempATis, $ator]);
        if ($ats == 0) {
          $atPercentage = 0;
        } else {
          $atPercentage = ($ator / $ats) * 100;
        }
        $totalATPercentageFormatted = number_format($atPercentage, 2);
        // end AT

        // 50cc AT
        $ccATis = MotorbikeInfo::where('motorType', 3)
        ->where('motorStatus', '=', 1)
        ->count();
        $tempCCATis = MotorbikeInfo::where('motorType', 3)
        ->where('motorStatus', '=', 5)
        ->count();
        $ccATor = MotorbikeInfo::where('motorType', 3)
        ->where('motorStatus', '=', 2)
        ->count();
        
        $ccATs = array_sum([$ccATis, $tempCCATis, $ccATor]);
        if ($ccATs == 0) {
          $ccATPercentage = 0;
        } else {
          $ccATPercentage = ($ccATor / $ccATs) * 100;
        }
        $total50ccATPercentageFormatted = number_format($ccATPercentage, 2);
        // end 50cc AT
        
        // manual
        $mtis = MotorbikeInfo::where('motorType', 4)
        ->where('motorStatus', '=', 1)
        ->count();
        $tempMTis = MotorbikeInfo::where('motorType', 4)
        ->where('motorStatus', '=', 5)
        ->count();
        $mtor = MotorbikeInfo::where('motorType', 4)
        ->where('motorStatus', '=', 2)
        ->count();
        
        $mts = array_sum([$mtis, $tempMTis, $mtor]);
        if ($mts == 0) {
          $mtPercentage = 0;
        } else {
          $mtPercentage = ($mtor / $mts) * 100;
        }
        $totalMTPercentageFormatted = number_format($mtPercentage, 2);
        // end manual

        $totalMotors = array_sum(
          [
            $bigATs, 
            $ats, 
            $ccATs, 
            $mts
          ]);
        $totalInstock = array_sum(
          [
            $bigATis, 
            $tempBigATis, 
            $atis, 
            $tempATis,
            $ccATis, 
            $tempCCATis,
            $mtis,
            $tempMTis
          ]);
        $totalOnRent = array_sum(
          [$bigATor, 
          $ator, 
          $ccATor, 
          $mtor
        ]);

        if ($totalMotors == 0) {
            $totalPercentage = 0;
          } else {
            $totalPercentage = ($totalOnRent / $totalMotors) * 100;
        }
        $totalPercentageFormatted = number_format($totalPercentage, 2);
        // end manual
        /* end rental percentage*/

        return view('content.reports.rentals.daily-rental', 
        [
            'rentals' => $rentals,
            'customer_contacts' => $customer_contacts,
            'rental_deposits' => $rental_deposits,
            'newRentals' => $newRentals,
            'extensions' => $extensions,
            'returns' => $returns,
            'exchangeMotors' => $exchangeMotors,
            'exchangeDeposits' => $exchangeDeposits,
            'totals' => $totals,

            'bigATs' => $bigATs,
            'bigATis' => $bigATis,
            'bigATor' => $bigATor,
            'bigATPercentage' => $bigATPercentage,
            'totalBigATPercentageFormatted' => $totalBigATPercentageFormatted,

            'ats' => $ats,
            'atis' => $atis,
            'ator' => $ator,
            'atPercentage' => $atPercentage,
            'totalATPercentageFormatted' => $totalATPercentageFormatted,

            'ccATs' => $ccATs,
            'ccATis' => $ccATis,
            'ccATor' => $ccATor,
            'ccATPercentage' => $ccATPercentage,
            'total50ccATPercentageFormatted' => $total50ccATPercentageFormatted,

            'mts' => $mts,
            'mtis' => $mtis,
            'mtor' => $mtor,
            'mtPercentage' => $mtPercentage,
            'totalMTPercentageFormatted' => $totalMTPercentageFormatted,

            'totalMotors' => $totalMotors,
            'totalInstock' => $totalInstock,
            'totalOnRent' => $totalOnRent,
            'totalPercentage' => $totalPercentage,
            'totalPercentageFormatted' => $totalPercentageFormatted
        ]);
    }
}
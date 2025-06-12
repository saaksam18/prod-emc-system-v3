<?php

namespace App\Http\Controllers\BasicData;
use Illuminate\Http\Response;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MotorbikeInfo;
use App\Models\operations\CustomerModel;
use App\Models\operations\DepositModel;
use App\Models\operations\ExchangeMotorModel;
use App\Models\CountriesModel;
use App\Models\User;
use App\Models\operations\RentalModel;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AddMotorbike extends Controller
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
      $motorbike_model_drop = MotorbikeInfo::select('motorModel')->distinct()->get();
      $motorbike_no_drop = MotorbikeInfo::select('motorno')->distinct()->get();
      $motorbike_color_drop = MotorbikeInfo::select('motorColor')->distinct()->get();
      $motorbike_price_drop = MotorbikeInfo::where('totalPurchasePrice', '>', 0)->select('totalPurchasePrice')->distinct()->get();

      $motorbikes = MotorbikeInfo::with('user')
      ->when($request->motorType != null, function ($q) use ($request) {
        return $q->where('motorType', 'LIKE', '%' . $request->motorType . '%');
      }, function ($q) {
        return $q->orWhere(function ($query) {
          $query->where('motorStatus', '!=', 6);
        });
      })
      ->when($request->motorno != null, function ($q) use ($request) {
        return $q->where('motorno', 'LIKE', '%' . $request->motorno . '%');
      })
      ->when($request->purchaseDate != null, function ($q) use ($request) {
        return $q->where('purchaseDate', 'LIKE', '%' . $request->purchaseDate . '%');
      })
      ->when($request->motorColor != null, function ($q) use ($request) {
        return $q->where('motorColor', 'LIKE', '%' . $request->motorColor . '%');
      })
      ->when($request->motorStatus != null, function ($q) use ($request) {
        return $q->where('motorStatus', $request->motorStatus);
      })
      ->when($request->motorModel != null, function ($q) use ($request) {
        return $q->where('motorModel', 'LIKE', '%' . $request->motorModel . '%');
      })
      ->when($request->totalPurchasePrice != null, function ($q) use ($request) {
        return $q->where('totalPurchasePrice', 'LIKE', '%' . $request->totalPurchasePrice . '%');
      })
      ->sortable()->paginate(50);
      
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
        
        $motorbikeCounts = MotorbikeInfo::groupBy('motorModel')
                            ->where('motorStatus', '=', 1)
                            ->orWhere('motorStatus', '=', 5)
                            ->selectRaw('motorModel, COUNT(*) as total_count')
                            ->get();
                            
        $motorbikeTotalCounts = MotorbikeInfo::where('motorStatus', '=', 1)
        ->orWhere('motorStatus', '=', 5)
        ->count();

        
        $motor = MotorbikeInfo::all();
        if ($motor != null) {
          $totalInstock = MotorbikeInfo::where(function ($query) {
            $query->where('motorStatus', 1);
            })
          ->count();
          
          $totalOnRent = MotorbikeInfo::where('motorStatus', 2)
          ->count();

          $tempReturn = MotorbikeInfo::where('motorStatus', 5)
          ->count();

          $totalMotors = array_sum([$totalInstock, $totalOnRent, $tempReturn]);

          /* Today Trans */
          $rent_today = MotorbikeInfo::where(function ($query) {
            $query->whereDate('updated_at', now())
                ->where('motorStatus', 2);
            })->count();
            
          $return_today = MotorbikeInfo::where(function ($query) {
            $query->whereDate('updated_at', now())
                ->where('motorStatus', 1);
            })->count();
          /* End Today Trans */
        } else {
          $totalInstock = 0;
          $totalOnRent = 0;
          $tempReturn = 0;
          $totalMotors = 0;
        }

      return view('content.motorbikes.index', 
      [
          'motorbikes' => $motorbikes,
          'motorbike_model_drop' => $motorbike_model_drop,
          'motorbike_no_drop' => $motorbike_no_drop,
          'motorbike_color_drop' => $motorbike_color_drop,
          'motorbike_price_drop' => $motorbike_price_drop
      ]);
    }

    public function show(Request $request, $id)
    {
        $customers = CustomerModel::all();
        $rental_price_drop = RentalModel::where(function ($q) use ($id) {
          $q->where('motorID', $id)
            ->where('transactionType', '!=', 'Add Coming Date')
            ->where('transactionType', '!=', 'EX. Deposit')
            ->where('price', '<>', 0);
        })
        ->select('price')
        ->distinct()
        ->get();

        $rental_tran_drop = RentalModel::where(function ($q) use ($id) {
          $q->where('motorID', $id)
            ->where('transactionType', '!=', 'Add Coming Date')
            ->where('transactionType', '!=', 'EX. Deposit');
        })
        ->select('transactionType')->distinct()->get();

        $motorbike_details = MotorbikeInfo::where('motorID', $id)
        ->get();
        
        $motorbikes = RentalModel::with(['motorInfor', 'customer', 'user'])
        ->when($request->rentalDay !== null, function ($q) use ($request) {
            $q->where('rentalDay', 'LIKE', '%' . $request->rentalDay . '%');
        })
        ->when($request->returnDate !== null, function ($q) use ($request) {
          $q->where('returnDate', 'LIKE', '%' . $request->returnDate . '%');
        })
        ->when($request->transactionType !== null, function ($q) use ($request) {
            $q->where('transactionType', $request->transactionType);
        })
        ->when($request->CustomerName !== null, function ($q) use ($request) {
            $q->whereHas('customer', function ($query) use ($request) {
              $query->where('CustomerName', 'LIKE', '%' . $request->CustomerName . '%');
            });
        })
        ->where(function ($q) use ($id) {
              $q->where('motorID', $id)
                ->where('transactionType', '!=', 'Add Coming Date')
                ->where('transactionType', '!=', 'EX. Deposit');
        })
        ->sortable()->paginate(50);

        foreach ($motorbikes as $motorbike) {
          if ($motorbike->transactionType === 'New Rental' || $motorbike->transactionType === 'Extension' || $motorbike->transactionType === 'Return') {
            $motorbike->new_price = $motorbike->price;
          } else {
            $motorbike->new_price = 0;
          }
        }

        $users = User::all();

        $rentals = RentalModel::where('motorID', $id)->get(); // To knows if motorbike already rent or not.

        // rent to how many customer
        // - total rental transactions
        $trts = RentalModel::select('customerID')
        ->where('motorID', $id)
        ->distinct()->get();
        // - total rent to customer
        $trtc = $trts->count();

        // first_rental_date
        $frd = RentalModel::where('motorID', $id)
        ->orderBy('rentalID', 'asc')
        ->limit(1)
        ->value('rentalDay'); //Get data from RentalModel where first new rental with motorID created.
        
        // last_return_date
        $lrd = RentalModel::where('motorID', $id)
        ->orderBy('rentalID', 'desc')
        ->limit(1)
        ->value('returnDate'); //Get data from RentalModel where last return transaction with motorID created.
        
        // total_on_rental_days
        $ealy_return = RentalModel::where('motorID', $id)
        ->where('transactionType', 'Return')
        ->sum('rentalPeriod');

        $total_rent_period = RentalModel::where('motorID', $id)
        ->where(function ($query) {
          $query->where('transactionType', 'New Rental')
                ->orWhere('transactionType', 'Extension');
        })
        ->sum('rentalPeriod'); 

        $tords = $total_rent_period + $ealy_return; // Day between (FRD -> LRD) *Must calculated the refund date*

        // total_instock_days
        $purchaseDate = MotorbikeInfo::where('motorID', $id)->value('purchaseDate');
        $purchaseDate_formated = Carbon::parse($purchaseDate);
        $today = Carbon::now();

        $total_in_stock = $purchaseDate_formated->diffInDays($today);
        $tids = $total_in_stock - $tords; // Calculate (Present-PurchaseDate) - (TORD)
        // total_repair_times
        $trts = 0;

        // total_exchange_times_to
        $tetts = ExchangeMotorModel::where('currMotorID', $id)
        ->count(); // Get how many motorID in currMotorID in ExchangeMotorModel

        // total_exchange_times_from
        $tetfs = ExchangeMotorModel::where('preMotoID', $id)
        ->count(); // Get how many motorID in preMotorID in ExchangeMotorModel
        
        // total_refund_price
        $ealy_return_refund = RentalModel::where('motorID', $id)
        ->where('transactionType', 'Return')
        ->sum('price');

        $total_rent_price = RentalModel::where('motorID', $id)
        ->where(function ($query) {
          $query->where('transactionType', 'New Rental')
                ->orWhere('transactionType', 'Extension');
        })
        ->sum('price'); 

        $trp = $total_rent_price + $ealy_return_refund;

        // total_motorbike_profit
        $total_purchase_price = MotorbikeInfo::where('motorID', $id)
        ->value('totalPurchasePrice');
        $tmp = $trp - $total_purchase_price;

        return view('content.motorbikes.show', 
      [
        'customers' => $customers,
        'rental_tran_drop' => $rental_tran_drop,
        'rental_price_drop' => $rental_price_drop,

        'motorbike_details' => $motorbike_details,
        'motorbikes' => $motorbikes,
        'users' => $users,
        'rentals' => $rentals,

        'frd' => $frd,
        'lrd' => $lrd,
        'tords' => $tords,
        'total_in_stock' => $total_in_stock,
        'tids' => $tids,
        'trts' => $trts,
        'trtc' => $trtc,
        'tetts' => $tetts,
        'tetfs' => $tetfs,

        'trp' => $trp,
        'tmp' => $tmp
      ]);
    }

    public function printStock(Request $request)
{
    $motorbikes = MotorbikeInfo::where('motorStatus', '=', 1)
    ->orWhere('motorStatus', '=', 5)
    ->orderBy('motorno')->get();

        /* daily rental percentage */
        $bigATs = MotorbikeInfo::orWhere(function ($query) {
          $query->where('motorType', 1)
                ->where('motorStatus', '!=', 3)
                ->where('motorStatus', '!=', 4);
        })
        ->count();

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

        if ($bigATs == 0) {
          $bigATPercentage = 0;
        } else {
          $bigATPercentage = ($bigATor / $bigATs) * 100;
        }
        
        $totalBigATPercentageFormatted = number_format($bigATPercentage, 2);
        // end bigAT
        
        // AT
        $ats = MotorbikeInfo::orWhere(function ($query) {
          $query->where('motorType', 2)
                ->where('motorStatus', '!=', 3)
                ->where('motorStatus', '!=', 4);
        })
        ->count();

        $atis = MotorbikeInfo::where('motorType', 2)
        ->where('motorStatus', '=', 1)
        ->count();
        $tempATis = MotorbikeInfo::where('motorType', 2)
        ->where('motorStatus', '=', 5)
        ->count();
        $ator = MotorbikeInfo::where('motorType', 2)
        ->where('motorStatus', '=', 2)
        ->count();

        if ($ats == 0) {
          $atPercentage = 0;
        } else {
          $atPercentage = ($ator / $ats) * 100;
        }
        
        $totalATPercentageFormatted = number_format($atPercentage, 2);
        // end AT

        // 50cc AT
        $ccATs = MotorbikeInfo::orWhere(function ($query) {
          $query->where('motorType', 3)
                ->where('motorStatus', '!=', 3)
                ->where('motorStatus', '!=', 4);
        })
        ->count();

        $ccATis = MotorbikeInfo::where('motorType', 3)
        ->where('motorStatus', '=', 1)
        ->count();
        $tempCCATis = MotorbikeInfo::where('motorType', 3)
        ->where('motorStatus', '=', 5)
        ->count();
        $ccATor = MotorbikeInfo::where('motorType', 3)
        ->where('motorStatus', '=', 2)
        ->count();

        if ($ccATs == 0) {
          $ccATPercentage = 0;
        } else {
          $ccATPercentage = ($ccATor / $ccATs) * 100;
        }
        
        $total50ccATPercentageFormatted = number_format($ccATPercentage, 2);

        // end 50cc AT
        
        // manual
        $mts = MotorbikeInfo::orWhere(function ($query) {
          $query->where('motorType', 4)
                ->where('motorStatus', '!=', 3)
                ->where('motorStatus', '!=', 4);
        })
        ->count();

        $mtis = MotorbikeInfo::where('motorType', 4)
        ->where('motorStatus', '=', 1)
        ->count();
        $tempMTis = MotorbikeInfo::where('motorType', 4)
        ->where('motorStatus', '=', 5)
        ->count();
        $mtor = MotorbikeInfo::where('motorType', 4)
        ->where('motorStatus', '=', 2)
        ->count();

        if ($mts == 0) {
          $mtPercentage = 0;
        } else {
          $mtPercentage = ($mtor / $mts) * 100;
        }
        
        $totalMTPercentageFormatted = number_format($mtPercentage, 2);
        // end manual
        /* end daily rental percentage*/

        $totalMotors = array_sum([$bigATs, $ats, $ccATs, $mts]);
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
        $totalOnRent = array_sum([$bigATor, $ator, $ccATor, $mtor]);

        if ($totalMotors == 0) {
            $totalPercentage = 0;
          } else {
            $totalPercentage = ($totalOnRent / $totalMotors) * 100;
        }
        
        $totalPercentageFormatted = number_format($totalPercentage, 2);

        $motorbikeCounts = MotorbikeInfo::groupBy('motorModel')
                            ->where('motorStatus', '=', 1)
                            ->orWhere('motorStatus', '=', 5)
                            ->selectRaw('motorModel, COUNT(*) as total_count')
                            ->get();
                            
        $motorbikeTotalCounts = MotorbikeInfo::where('motorStatus', '=', 1)
        ->orWhere('motorStatus', '=', 5)
        ->count();

    return view('content.motorbikes.print', 
    [
      'motorbikes' => $motorbikes,

      'bigATs' => $bigATs,
      'bigATis' => $bigATis,
      'tempBigATis' => $tempBigATis,
      'bigATor' => $bigATor,
      'bigATPercentage' => $bigATPercentage,
      'totalBigATPercentageFormatted' => $totalBigATPercentageFormatted,

      'ats' => $ats,
      'atis' => $atis,
      'tempATis' => $tempATis,
      'ator' => $ator,
      'atPercentage' => $atPercentage,
      'totalATPercentageFormatted' => $totalATPercentageFormatted,

      'ccATs' => $ccATs,
      'ccATis' => $ccATis,
      'tempCCATis' => $tempCCATis,
      'ccATor' => $ccATor,
      'ccATPercentage' => $ccATPercentage,
      'total50ccATPercentageFormatted' => $total50ccATPercentageFormatted,

      'mts' => $mts,
      'mtis' => $mtis,
      'tempMTis' => $tempMTis,
      'mtor' => $mtor,
      'mtPercentage' => $mtPercentage,
      'totalMTPercentageFormatted' => $totalMTPercentageFormatted,

      'totalMotors' => $totalMotors,
      'totalInstock' => $totalInstock,
      'totalOnRent' => $totalOnRent,
      'totalPercentage' => $totalPercentage,
      'totalPercentageFormatted' => $totalPercentageFormatted,
      'motorbikeCounts' => $motorbikeCounts,
      'motorbikeTotalCounts' => $motorbikeTotalCounts
    ]);
}

    public function create()
    {
        return view('content.motorbikes.create');
    }
  
    public function store(Request $request)
    {
        $request-> validate([
            'motorno' => 'required|unique:tbl_motorInfor'
        ]);

        try {
            $motorbike = new MotorbikeInfo();
            $motorbike->motorno = $request->input('motorno');
            $motorbike->year = $request->input('year');
            $motorbike->plateNo = $request->input('plateNo');
            $motorbike->engineNo = $request->input('engineNo');
            $motorbike->chassisNo = $request->input('chassisNo');
            $motorbike->motorColor = $request->input('motorColor');
            $motorbike->motorType = $request->input('motorType');
            $motorbike->motorModel = $request->input('motorModel');
            $motorbike->purchaseDate = $request->input('purchaseDate');
            $motorbike->motorStatus = '1';
            $motorbike->compensationPrice = $request->input('compensationPrice');
            $motorbike->totalPurchasePrice = $request->input('totalPurchasePrice');
            $motorbike->userID = auth()->user()->id;

            $motorbike->save();
            return redirect()->back()->with('success', 'Motorbike Successfully Added!');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('success', 'Motorbike number already exists.');
        }
    }

    public function edit($id)
    {
        $motorbike = MotorbikeInfo::find($id);

        return view('content.motorbikes.edit', compact('motorbike'));
    }

    public function soldStolen($id)
    {
        $countriesList = CountriesModel::all();
        $motorbikes = MotorbikeInfo::find($id);
        $customers = CustomerModel::all();
        $users = User::all();

        return view('content.motorbikes.sold-and-stolen', compact('motorbikes', 'customers', 'countriesList', 'users'));
    }

    public function soldStolenContract(Request $request, $id)
    {
      $request-> validate([
        'CustomerName' => 'required',
        'transactionType' => 'required',
        'returnDate' => 'required',
        'staffId' => 'required',
        'price' => 'required',
      ],
      [
        'CustomerName' => 'Customer Name is missing',
        'transactionType' => 'Transaction is missing',
        'returnDate' => 'Return Date is missing',
        'staffId' => 'Incharge Staff is missing',
        'price' => 'Price is missing',
      ]);
        try{
          $cDate = Carbon::now()->addHours(7);
          $formattedNowRent = $cDate->format('Y-m-d H:i:s');

          $old_rentalID = RentalModel::where('motorID', $id)
          ->orderBy('rentalID', 'desc')
          ->limit(1)
          ->value('rentalID');

          // Change old rental is_Active to inA
          // inA = is not Active
          if ($old_rentalID != null) {
            $rental_inA = RentalModel::findOrFail($old_rentalID);
            $rental_inA->is_Active = 0;
            $rental_inA->userID = auth()->user()->id;
            $rental_inA->updated_at = $cDate;
            $rental_inA->save();
            
          // Saving to rental
          $CustomerName = request('CustomerName');
          $customerID = DB::table('tbl_customer')->where('CustomerName', $CustomerName)->value('customerID');

          $rental = new RentalModel;
          $rental->customerID = $customerID;
          $rental->motorID = $id;
          $rental->transactionType = $request->input('transactionType');
          $rental->rentalDay = $rental_inA->rentalDay;
          $rental->returnDate = $request->input('returnDate');
          $rental->rentalPeriod = 0;
          $rental->price = $request->input('price');
          $rental->staff_id = $request->input('staffId');
          $rental->is_Active = 0;
          $rental->userID = auth()->user()->id;
          $rental->created_at = $cDate;
          $rental->updated_at = $cDate;
          $rental->save();
          // End Saving to rental
          } else {
            // Saving to rental
            $CustomerName = request('CustomerName');
            $customerID = DB::table('tbl_customer')->where('CustomerName', $CustomerName)->value('customerID');
  
            $rental = new RentalModel;
            $rental->customerID = $customerID;
            $rental->motorID = $id;
            $rental->transactionType = $request->input('transactionType');
            $rental->rentalDay = $request->input('returnDate');
            $rental->returnDate = $request->input('returnDate');
            $rental->rentalPeriod = 0;
            $rental->price = $request->input('price');
            $rental->staff_id = $request->input('staffId');
            $rental->is_Active = 0;
            $rental->userID = auth()->user()->id;
            $rental->created_at = $cDate;
            $rental->updated_at = $cDate;
            $rental->save();
            // End Saving to rental
          }
          // End Change old rental is_Active to inA

          // Deposit
          // Change old Depsit is_Active to inA
          $deposit_rentalIDs = DepositModel::where('rentalID', $old_rentalID)
          ->where('is_Active', 1)
          ->orderBy('depositID', 'desc')
          ->update([
            'is_Active' => 0,
            'userID' => auth()->user()->id,
            'updated_at' => $cDate,
          ]);
          // End Deposit
          
          // Change motor status
          if ($rental->transactionType == 3){
            $motorbike = MotorbikeInfo::find($rental->motorID);
            $motorbike->customerID = $customerID;
            $motorbike->motorStatus = 3;
            $motorbike->is_Active = 0;
            $motorbike->updated_at = $cDate;
            $motorbike->save();
          } elseif ($rental->transactionType == 4){
            $motorbike = MotorbikeInfo::find($rental->motorID);
            $motorbike->customerID = $customerID;
            $motorbike->motorStatus = 4;
            $motorbike->is_Active = 0;
            $motorbike->updated_at = $cDate;
            $motorbike->save();
          }
          // End change motor status
          
      if ($rental->transactionType == 3){
        return redirect()->route('motorbikes.index')->with('success', 'Motorbike has been sold');
      } elseif ($rental->transactionType == 4){
        return redirect()->route('motorbikes.index')->with('success', 'Motorbike has been stolen');
      }
      } catch (Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('error', 'Motorbike updated failed!');
      }
    }

    public function update(Request $request, $id)
    {
        $motorbike = MotorbikeInfo::find($id);
        $motorbike->motorno = $request->input('motorno');
        $motorbike->year = $request->input('year');
        $motorbike->plateNo = $request->input('plateNo');
        $motorbike->engineNo = $request->input('engineNo');
        $motorbike->chassisNo = $request->input('chassisNo');
        $motorbike->motorColor = $request->input('motorColor');
        $motorbike->motorType = $request->input('motorType');
        $motorbike->motorModel = $request->input('motorModel');
        $motorbike->purchaseDate = $request->input('purchaseDate');
        $motorbike->motorStatus = $request->input('motorStatus');
        $motorbike->compensationPrice = $request->input('compensationPrice');
        $motorbike->totalPurchasePrice = $request->input('totalPurchasePrice');
        $motorbike->userID = auth()->user()->id;

        $motorbike->save();

        return redirect()->route('motorbikes.index', $motorbike->id)->with('success', 'Motorbike Successfully Updated!');
    }
    public function destroy($id)
    {
        $motorbike = MotorbikeInfo::findOrFail($id);
        $motorbike->motorStatus = 6;
        $motorbike->save();

        return redirect()->route('motorbikes.index', $motorbike->id)->with('success', 'Motorbike deleted successfully.');
    }
    
}

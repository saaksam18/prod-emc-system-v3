<?php

namespace App\Http\Controllers\operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

use App\Models\operations\CustomerModel;
use App\Models\operations\DepositModel;
use App\Models\operations\ContactModel;
use App\Models\operations\RentalModel;
use App\Models\operations\VisaModel;
use App\Models\operations\WPModel;
use App\Models\CountriesModel;
use App\Models\MotorbikeInfo;
use App\Models\User;

class AddCustomer extends Controller
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
        $customer_gender = CustomerModel::select('gender')->distinct()->get();

        // Service amounts
        $scooters = RentalModel::where('transactionType', 'New Rental')
        ->select('customerID')
        ->distinct()
        ->get();
        $scooter = $scooters->count();
        
        $visas = VisaModel::select('customerID')
        ->distinct()
        ->get();
        $visa = $visas->count();
        
        $wps = WPModel::select('customerID')
        ->distinct()
        ->get();
        $wp = $wps->count();
        // End Service amounts

        $customers = CustomerModel::with('deposit','contact', 'user')
        ->when($request->CustomerName !== null, function ($q) use ($request) {
            $q->where('CustomerName', 'LIKE', '%' . $request->CustomerName . '%');
        })
        ->when($request->nationality !== null, function ($q) use ($request) {
            $q->where('nationality', 'LIKE', '%' . $request->nationality . '%');
        })
        ->when($request->address !== null, function ($q) use ($request) {
            $q->where('address', 'LIKE', '%' . $request->address . '%');
        })
        ->when($request->gender !== null, function ($q) use ($request) {
            $q->where('gender', 'LIKE', '%' . $request->gender . '%');
        })
        ->sortable()
        ->paginate(50);

      $customer_contacts = ContactModel::where('is_Active' , 1)->get();
            
        return view ('content.customers.index', [
            'customers' => $customers,
            'countriesList' => $countriesList,
            'customer_gender' => $customer_gender,
            'customer_contacts' => $customer_contacts,
            'scooter' => $scooter,
            'visa' => $visa,
            'wp' => $wp
        ]);
    }
  public function create()
    {
        $countriesList = CountriesModel::all();
        return view('layouts.sections.operation.add-motor-rental.create', compact('countriesList'));
    }
  
    public function store(Request $request)
    {
        $request-> validate([
            'CustomerName' => 'required|unique:tbl_customer',
            'inputs.*.contactDetail' => 'required',
        ], 
        [
            'CustomerName' => 'Customer Name is missing',
            'inputs.*.contactDetail' => 'Contact Detail is missing',
        ]);
        $cDate = Carbon::now()->addHours(7);

        try {
            // SAVE TBL_CUSTOMER
            $customer = new CustomerModel();
            $customer->CustomerName = $request->input('CustomerName');
            $customer->gender = $request->input('gender');
            $customer->nationality = $request->input('nationality');
            $customer->address = $request->input('address');
            $customer->comment = $request->input('comment');
            $customer->userID = auth()->user()->id;
            $customer->created_at = $cDate;
            $customer->updated_at = $cDate;
            $customer->save();
            // END SAVE TBL_CUSTOMER
            
            // Contact
            $CustomerName = request('CustomerName');
            $customerID = CustomerModel::where('CustomerName', $CustomerName)->value('customerID');

            $contacts = [];
            foreach ($request->inputs as $value) {
            $contacts[] = [
                'customerID' => $customerID,
                'contactType' => $value['contactType'],
                'contactDetail' => $value['contactDetail'],
                'is_Active' => 1,
                'userID' => auth()->user()->id,
                'created_at' => $cDate,
                'updated_at' => $cDate,
            ];
            }
            ContactModel::insert($contacts);
            // End Contact
        
        return redirect()->back()->with('success', 'Customer Successfully Added!');
        } catch (Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('error', 'Customer Already Exited!');
        }
    }

    public function show(Request $request ,$id)
    {
        $users = User::all();

        $customers = CustomerModel::with(['deposit', 'contact', 'user'])
        ->where('customerID', $id)
        ->get();

        /* Rental */
        $rentalModel = RentalModel::where('customerID', $id)->get();
        if ($rentalModel) {
            $rentals = RentalModel::with('user')
            ->where('customerID', $id)
            ->where('is_Active', 1)
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->get();
            
            $userID = RentalModel::with('user')
            ->where('customerID', $id)
            ->where('is_Active', 1)
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->value('staff_id');

            $staff_name = User::where('id', $userID)->value('name');
    
            $rental_status = RentalModel::where('customerID', $id)
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->get();
            
            $last_return_date = RentalModel::where('customerID', $id)
            ->where('transactionType', 'Return')
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->first();
    
            $last_extension_date = RentalModel::where('customerID', $id)
            ->where('transactionType', 'Extension')
            ->orderBy('rentalID', 'desc')
            ->limit(1)
            ->first();
            
            $begin_rentals = RentalModel::where('customerID', $id)
            ->where('transactionType', 'New Rental')
            ->orderBy('rentalID', 'asc')
            ->limit(1)
            ->get();
    
            $toE = RentalModel::where('customerID', $id)
            ->where('transactionType', 'Extension')
            ->count();
            
            $sub_trP = RentalModel::where('customerID', $id)
            ->where(function ($query) {
                $query->where('transactionType', 'New Rental')
                      ->orWhere('transactionType', 'Extension');
              })
            ->sum('rentalPeriod');

            $daysDiff = RentalModel::where('customerID', $id)
            ->where('transactionType', 'Return')
            ->sum('rentalPeriod');

            $trP = $sub_trP - $daysDiff;
            
            $total_rent_price = RentalModel::where('customerID', $id)
            ->where(function ($query) {
                $query->where('transactionType', 'New Rental')
                      ->orWhere('transactionType', 'Extension');
              })
            ->sum('price');

            $ealy_return_refund = RentalModel::where('motorID', $id)
            ->where('transactionType', 'Return')
            ->sum('price');

            $trPrice = $total_rent_price + $ealy_return_refund;
            
        } else {
            $rentals = 0;
            $rental_status = null;
            $last_return_date = null;
            $last_extension_date = null;
            $begin_rentals = null;
            $toE = null;
            $sub_trP = null;
            $trP = null;
            $trPrice = null;
        }

        $motorbike_no_drop = MotorbikeInfo::select('motorno')->distinct()->get();
        $customer_contact_type = ContactModel::where('customerID', $id)->where('is_Active', 1)->select('contactType')->distinct()->get();
        $customer_contact = ContactModel::where('customerID', $id)->where('is_Active', 1)->select('contactDetail')->distinct()->get();
        $rental_tran_drop = RentalModel::where('customerID', $id)->select('transactionType')->distinct()->get();
        $rental_price_drop = RentalModel::where('customerID', $id)->where('price', '<>', 0)->select('price')->distinct()->get();

        $rental_logs = RentalModel::with(['customer', 'contact', 'deposit', 'motorInfor', 'user'])
        ->when($request->returnDate !== null, function ($q) use ($request) {
            $q->where('returnDate', $request->returnDate);
        })
        ->when($request->motorno !== null, function ($q) use ($request) {
            $q->whereHas('motorInfor', function ($query) use ($request) {
              $query->where('motorno', 'LIKE', '%' . $request->motorno . '%');
            });
        })
        ->when($request->contactType !== null, function ($q) use ($request) {
            $q->whereHas('contact', function ($query) use ($request) {
              $query->where('contactType', 'LIKE', '%' . $request->contactType . '%');
            });
        })
        ->when($request->contactDetail !== null, function ($q) use ($request) {
            $q->whereHas('contact', function ($query) use ($request) {
              $query->where('contactDetail', 'LIKE', '%' . $request->contactDetail . '%');
            });
        })
        ->when($request->transactionType !== null, function ($q) use ($request) {
          $q->where('transactionType', 'LIKE', '%' . $request->transactionType . '%');
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
        ->where(function ($q) use ($id) {
            if ($id != null) {
                $q->where('customerID', $id);
            }
        })
        ->sortable()
        ->paginate(50);

        $rental_deposits = DepositModel::all();
        // Get contact of customerID
        $contacts = ContactModel::where('customerID', $id)->where('is_Active', 1)->get();
        /* End Rental */

        /* Visa */
        $visaModel = VisaModel::where('customerID', $id)->get();
        if ($visaModel) {
            $visas = VisaModel::where('customerID', $id)
            ->orderBy('visaID', 'desc')
            ->limit(1)
            ->get();

            $vToE = VisaModel::where('customerID', $id)
            ->count('visaID');

            $vLE = VisaModel::where('customerID', $id)
            ->where('is_Active', 0)
            ->orderBy('visaID', 'desc')
            ->limit(1)
            ->get();

            $vLEs = VisaModel::where('customerID', $id)
            ->orderBy('visaID', 'desc')
            ->limit(1)
            ->first();
        } else {
            $visas = 0;
            $vToE = null;
            $vLE = null;
            $vLEs = null;
        }

        if ($vLEs) {
            // Convert retrieved objects to Carbon instances
            $v_last_expirations = Carbon::parse($vLEs->expireDate);
            $v_last_extensions = Carbon::parse($vLEs->updated_at);
        } else {
            $v_last_expirations = null;
            $v_last_extensions = null;
        }
    
        $visa_logs = VisaModel::with('customer', 'user')
        ->when($request->status !== null, function ($q) use ($request) {
          $q->where('is_Active', 'LIKE', '%' . $request->status . '%');
        })
        ->when($request->visaType !== null, function ($q) use ($request) {
          $q->where('visaType', 'LIKE', '%' . $request->visaType . '%');
        })
        ->when($request->expireDate !== null, function ($q) use ($request) {
          $q->where('expireDate', 'LIKE', '%' . $request->expireDate . '%');
        })
        ->when($request->staff_id !== null, function ($q) use ($request) {
          $q->where('staff_id', 'LIKE', '%' . $request->staff_id . '%');
        })
        ->where(function ($q) use ($id) {
                $q->where('customerID', $id);
        })
        ->sortable()
        ->paginate(50);
        
        $statuss = VisaModel::select('is_Active')->distinct()->get();
        /* End Visa */
        
        /* Work Permit */
        $wpModel = WPModel::where('customerID', $id)->get();
        if ($wpModel) {
            $wps = WPModel::where('customerID', $id)
            ->orderBy('wpID', 'desc')
            ->limit(1)
            ->get();
    
            $wToE = WPModel::where('customerID', $id)
            ->count('wpID');
    
            $wLE = WPModel::where('customerID', $id)
            ->where('is_Active', 0)
            ->orderBy('wpID', 'desc')
            ->limit(1)
            ->get();
    
            $wLEs = WPModel::where('customerID', $id)
            ->orderBy('wpID', 'desc')
            ->limit(1)
            ->first();
        } else {
            $wps = 0;
            $wToE = null;
            $wLE = null;
            $wLEs = null;
        }

        if ($wLEs) {
            // Convert retrieved objects to Carbon instances
            $w_last_expirations = Carbon::parse($wLEs->wpExpireDate);
            $w_last_extensions = Carbon::parse($wLEs->updated_at);
        } else {
            $w_last_expirations = null;
            $w_last_extensions = null;
        }
        
        $wp_logs = WPModel::with('customer', 'user')
        ->when($request->status !== null, function ($q) use ($request) {
          $q->where('is_Active', 'LIKE', '%' . $request->status . '%');
        })
        ->when($request->expireDate !== null, function ($q) use ($request) {
          $q->where('wpExpireDate', 'LIKE', '%' . $request->expireDate . '%');
        })
        ->when($request->staff_id !== null, function ($q) use ($request) {
          $q->where('staff_id', 'LIKE', '%' . $request->staff_id . '%');
        })
        ->where(function ($q) use ($id) {
                $q->where('customerID', $id);
        })
        ->sortable()
        ->paginate(50);
        /* End Work Permit */

        return view('content.customers.show', 
        [
            'users' => $users,
            'customers' => $customers,

            'rental_price_drop' => $rental_price_drop,
            'rental_tran_drop' => $rental_tran_drop,
            'customer_contact_type' => $customer_contact_type,
            'customer_contact' => $customer_contact,
            'motorbike_no_drop' => $motorbike_no_drop,
            'rental_logs' => $rental_logs,
            'rental_deposits' => $rental_deposits,
            'contacts' => $contacts,
            'rentals' => $rentals,
            'staff_name' => $staff_name,
            'rental_status' => $rental_status,
            'begin_rentals' => $begin_rentals,
            'toE' => $toE,
            'trP' => $trP,
            'trPrice' => $trPrice,

            'visa_logs' => $visa_logs,
            'statuss' => $statuss,
            'visas' => $visas,
            'vToE' => $vToE,
            'vLE' => $vLE,
            'v_last_expirations' => $v_last_expirations,
            'v_last_extensions' => $v_last_extensions,

            'wp_logs' => $wp_logs,
            'wps' => $wps,
            'wToE' => $wToE,
            'wLE' => $wLE,
            'w_last_expirations' => $w_last_expirations,
            'w_last_extensions' => $w_last_extensions
        ]);
    }

    public function edit($id)
    {
        $customer = CustomerModel::find($id);
        $countriesList = CountriesModel::all();
        
        $old_contacts = ContactModel::where('customerID', $customer->customerID)
        ->where('is_Active', 1)
        ->get();

        return view('content.customers.edit', compact('customer', 'countriesList', 'old_contacts'));
    }

    public function update(Request $request, $id)
    {
        $request-> validate([
            'CustomerName' => 'required',
        ]);

        try {
            $customer = CustomerModel::find($id);
            $customer->CustomerName = $request->input('CustomerName');
            $customer->gender = $request->input('gender');
            $customer->nationality = $request->input('nationality');
            $customer->address = $request->input('address');
            $customer->comment = $request->input('comment');
            $customer->userID = auth()->user()->id;
            $customer->updated_at = now();
            $customer->save();

            return redirect()->route('customers.index', $customer->id)->with('success', 'Customer updated');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Customer update failed');
        }
    }
    public function destroy($id)
    {
        $customer = CustomerModel::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Motorbike deleted successfully.');
    }

      public function editContact($id)
    {   
        $customers = CustomerModel::all();
        
        $old_contacts = ContactModel::where('customerID', $id)
        ->where('is_Active', 1)
        ->get();
        $contactID = ContactModel::where('customerID', $id)->value('id');
        $contacts = ContactModel::findOrFail($contactID);
        return view('content.customers.edit-contact', compact('customers', 'contacts', 'old_contacts'));
    }

    public function updateContact(Request $request, $id) {

        $request-> validate([
            'CustomerName' => 'required|unique:tbl_customer',
            'inputs.*.contactDetail' => 'required',
            'old_contactType' => 'required',
            'old_contactDetail' => 'required',
        ], 
        [
            'CustomerName' => 'Customer Name is missing',
            'inputs.*.contactDetail' => 'Contact Detail is missing',
            'old_contactType' => 'Previous Contact Type is missing',
            'old_contactDetail' => 'Previous Contact Detail is missing',
        ]);
        $cDate = Carbon::now()->addHours(7);
        try {
            // Contact
            
            // Change old Contact is_Active to inA
            $old_contactID = ContactModel::where('customerID', $id)
            ->where('is_Active', 1)
            ->orderBy('id', 'desc')
            ->update([
            'is_Active' => 0,
            'userID' => auth()->user()->id,
            'updated_at' => $cDate,
            ]);

            $CustomerName = request('CustomerName');
            $customerID = CustomerModel::where('customerID', $CustomerName)->value('customerID');

            $contacts = [];
            foreach ($request->inputs as $value) {
            $contacts[] = [
                'customerID' => $id,
                'pre_contactType' => $request->input('old_contactType'),
                'pre_contactDetail' => $request->input('old_contactDetail'),
                'contactType' => $value['contactType'],
                'contactDetail' => $value['contactDetail'],
                'is_Active' => 1,
                'userID' => auth()->user()->id,
                'created_at' => $cDate,
                'updated_at' => $cDate,
            ];
            }
            ContactModel::insert($contacts);
            // End Contact
            
        return redirect()->back()->with('success', 'Customer contact changed');
        }catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Customer contact change failed.');
          }
    }
}

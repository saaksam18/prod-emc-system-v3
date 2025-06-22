<?php

namespace App\Http\Controllers\operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\CountriesModel;
use App\Models\operations\CustomerModel;
use App\Models\operations\VisaModel;
use App\Models\operations\ContactModel;
use App\Models\User;

class AddVisaCustomer extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    $countriesList = CountriesModel::all();
    $customers = CustomerModel::all();
    $customer_contact_type = ContactModel::where('is_Active', 1)->select('contactType')->distinct()->get();
    $customer_contact = ContactModel::where('is_Active', 1)->select('contactDetail')->distinct()->get();
    $customer_gender = CustomerModel::select('gender')->distinct()->get();

    $visa_type = VisaModel::where('is_Active', 1)->select('visaType')->distinct()->get();

      $visas = VisaModel::with(['customer', 'contact', 'user'])
      ->where(function ($q) use ($request) {
            $q->where('tbl_visa.is_Active', 1);
        })
      ->when($request->CustomerName !== null, function ($q) use ($request) {
          $q->whereHas('customer', function ($query) use ($request) {
            $query->where('CustomerName', 'LIKE', '%' . $request->CustomerName . '%');
          });
      })
      ->when($request->gender !== null, function ($q) use ($request) {
          $q->whereHas('customer', function ($query) use ($request) {
            $query->where('gender', $request->gender);
          });
      })
      ->when($request->nationality !== null, function ($q) use ($request) {
          $q->whereHas('customer', function ($query) use ($request) {
            $query->where('nationality', 'LIKE', '%' . $request->nationality . '%');
          });
      })
      ->when($request->visaType !== null, function ($q) use ($request) {
          $q->where('visaType', 'LIKE', '%' . $request->visaType . '%');
      })
      ->when($request->expireDate !== null, function ($q) use ($request) {
        $q->where('expireDate', 'LIKE', '%' . $request->expireDate . '%');
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
      ->sortable()
      ->paginate(50);
      
      $customer_contacts = ContactModel::where('is_Active' , 1)->get();
      $users = User::all();

        return view('content.visas.index', 
        [
            'visas' => $visas, 
            'visa_type' => $visa_type,
            'customer_contacts' => $customer_contacts, 
            'users' => $users, 
            'countriesList' => $countriesList, 
            'customers' => $customers,
            'customer_contact_type' => $customer_contact_type,
            'customer_contact' => $customer_contact,
            'customer_gender' => $customer_gender
        ]);
    }

    public function visaRemind()
    {

        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');

        $visas = VisaModel::with('customer')
        ->where('is_Active', 1)
        ->whereDate('remindDate', '<=', now())
        ->sortable()
        ->paginate(50);
        
        $i = 1;
        
        // visa customers
        $totalCustomers = VisaModel::where('is_Active', 1)
        ->whereDate('remindDate', '<=', now())
        ->count();
        
        $customer_contacts = ContactModel::where('is_Active' , 1)->get();
        $users = User::all();

        return view('content.visas.remind', 
        [
            'i' => $i,
            'visas' => $visas,
            'customer_contacts' => $customer_contacts, 
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $customers = CustomerModel::orderBy('customerID', 'desc')->get();
        $countriesList = CountriesModel::all();

        return view('content.visas.create', compact('customers', 'countriesList', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request-> validate([
            'CustomerName' => 'required',
            'expireDate' => 'required',
            'visaType' => 'required',
            'staffID' => 'required',
            'ppAmount' => 'required'
        ]);
        
        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');

        try{
            $CustomerName = request('CustomerName');
            $customerID = DB::table('tbl_customer')->where('CustomerName', $CustomerName)->value('customerID');

            $visaCustomer = new VisaModel;
            $visaCustomer->customerID = $customerID;
            $visaCustomer->amount = $request->input('ppAmount');
            $visaCustomer->visaType = $request->input('visaType');
            $visaCustomer->expireDate = $request->input('expireDate');
            $visaCustomer->staff_id = $request->input('staffID');
            $visaCustomer->is_Active = '1';
            $remindDate = Carbon::parse($request->input('expireDate'))->subMonth();
            $visaCustomer->remindDate = $remindDate;
            $visaCustomer->userID = auth()->user()->id;
            $visaCustomer->created_at = $cDate;
            $visaCustomer->updated_at = $cDate;
            $visaCustomer->save();

            return redirect()->back()->with('success', 'Visa customer successfully added.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Add visa customer failed.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');

        $visas = VisaModel::with('customer')
        ->where('is_Active', 1)
        ->whereDate('remindDate', '<=', now())
        ->sortable()
        ->paginate(50);
        
        $i = 1;
        
        // visa customers
        $totalCustomers = VisaModel::where('is_Active', 1)
        ->whereDate('remindDate', '<=', now())
        ->count();
        
        $customer_contacts = ContactModel::where('is_Active' , 1)->get();
        $users = User::all();

        return view('content.visas.remind', 
        [
            'i' => $i,
            'visas' => $visas,
            'customer_contacts' => $customer_contacts, 
            'users' => $users
        ]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visaShow(Request $request, $id)
    {

        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');
        
        $statuss = VisaModel::select('is_Active')->distinct()->get();

        $visas = VisaModel::with('customer', 'user')
        ->where('visaID', $id)
        ->get();

        foreach ($visas as $visa)
        {
            $customerID = $visa->customerID;
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
        ->where(function ($q) use ($customerID) {
                $q->where('customerID', $customerID);
        })
        ->sortable()
        ->paginate(50);
        
        $customer_contacts = ContactModel::where('is_Active' , 1)->get();
        $users = User::all();

        return view('content.visas.show', 
        [
            'statuss' => $statuss,
            'visas' => $visas,
            'visa_logs' => $visa_logs,
            'customer_contacts' => $customer_contacts, 
            'users' => $users
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countriesList = CountriesModel::all();
        $users = User::all();
        $customers = CustomerModel::orderBy('customerID', 'desc')->get();
        $visas = VisaModel::findOrFail($id);

        return view('content.visas.edit', compact('visas', 'customers', 'countriesList', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request-> validate([
            'amount' => 'required',
            'visaType' => 'required',
            'staffID' => 'required'
        ],
        [
            'amount' => 'Passport Amount is missing',
            'visaType' => 'Visa Type is missing',
            'staffID' => 'Incharge Staff is missing'
        ]);
        try{
        $visa = VisaModel::findOrFail($id);
        $old_visa = $visa->expireDate;
        $visa->amount = $request->input('amount');
        $visa->visaType = $request->input('visaType');
        if ($request->input('expireDate') != null) {
            $visa->expireDate = $request->input('expireDate');
            $remindDate = Carbon::parse($request->input('expireDate'))->subMonth();
            $visa->remindDate = $remindDate;
        } else {
            $visa->expireDate = $old_visa;
            $remindDate = Carbon::parse($old_visa)->subMonth();
            $visa->remindDate = $remindDate;
        }
        $visa->staff_id = $request->input('staffID');
        $visa->userID = auth()->user()->id;
        $visa->save();

        return redirect()->route('visas.index')->with('success', 'Customer data changed.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Customer data update failed.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reminded($id)
    {
        try {
        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');

        $visas = VisaModel::findOrFail($id);
        $visas->is_Active = 0;
        $visas->updated_at = $cDate;
        $visas->save();

        return redirect()->back()->with('success', 'Visa customer reminded successfully.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Visa permit customer remind failed.');
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');

        $visas = VisaModel::findOrFail($id);
        $visas->delete();

        return redirect()->back()->with('success', 'Visa customer deleted successfully.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Visa customer delete failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $visaCustomer = AddVisaCustomerModel::findOrFail($id);
        $visaCustomer->delete();

        return redirect()->back()->with('success', 'Visa customer deleted successfully.');
    }
}

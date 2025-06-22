<?php

namespace App\Http\Controllers\operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\CountriesModel;
use App\Models\operations\CustomerModel;
use App\Models\operations\WPModel;
use App\Models\operations\ContactModel;
use App\Models\User;

class AddWPCustomer extends Controller
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

      $wps = WPModel::with(['customer', 'contact', 'user'])
      ->where(function ($q) use ($request) {
            $q->where('tbl_wp.is_Active', 1);
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

        return view('content.wps.index', 
        [
            'wps' => $wps, 
            'users' => $users, 

            'customer_contacts' => $customer_contacts, 
            'countriesList' => $countriesList, 
            'customers' => $customers,
            'customer_contact_type' => $customer_contact_type,
            'customer_contact' => $customer_contact,
            'customer_gender' => $customer_gender
        ]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $wps = WPModel::with(['customer', 'contact', 'user'])
        ->where('is_Active', 1)
        ->whereDate('wpRemindDate', '<=', now())
        ->sortable()
        ->paginate(50);

        $i = 1;
        
      $customer_contacts = ContactModel::where('is_Active' , 1)->get();
      $users = User::all();

        return view('content.wps.remind', compact('i', 'wps', 'customer_contacts', 'users'));
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

        return view('content.wps.create', compact('customers', 'countriesList', 'users'));
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
            'wpExpireDate' => 'required',
            'staffID' => 'required'
        ]);

        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');

        try{
            $CustomerName = request('CustomerName');
            $customerID = DB::table('tbl_customer')->where('CustomerName', $CustomerName)->value('customerID');

            $wpCustomer = new WPModel;
            $wpCustomer->customerID = $customerID;
            $wpCustomer->wpExpireDate = $request->input('wpExpireDate');
            $wpCustomer->staff_id = $request->input('staffID');
            $wpCustomer->is_Active = 1;
            $remindDate = Carbon::parse($request->input('wpExpireDate'))->subMonth();
            $wpCustomer->wpRemindDate = $remindDate;
            $wpCustomer->userID = auth()->user()->id;
            $wpCustomer->created_at = $cDate;
            $wpCustomer->updated_at = $cDate;
            $wpCustomer->save();

            return redirect()->back()->with('success', 'Work permit customer created successfully.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Add work permit customer failed.');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers = CustomerModel::all();
        $users = User::all();
        $countriesList = CountriesModel::all();

        $wps = WPModel::findOrFail($id);

        return view('content.wps.edit', compact('customers', 'users', 'countriesList', 'wps'));
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
        $date_time = Carbon::now()->addHours(7);
        $formattedNowRent = $date_time->format('Y-m-d H:i:s');

        $request-> validate([
            'wpExpireDate' => 'required',
            'staffID' => 'required'
        ]);
        try {
            $wpCustomer = WPModel::findOrFail($id);
            $wpCustomer->wpExpireDate = $request->input('wpExpireDate');
            $remindDate = Carbon::parse($request->input('wpExpireDate'))->subMonth();
            $wpCustomer->wpRemindDate = $remindDate;
            $wpCustomer->staff_id = $request->input('staffID');
            $wpCustomer->updated_at = $date_time;
            $wpCustomer->save();
    
            return redirect()->route('work-permit.index')->with('success', 'Work permit customer updated successfully.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->route('work-permit.index')->with('error', 'Work permit customer update failed.');
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

        $wpCustomer = WPModel::findOrFail($id);
        $wpCustomer->is_Active = 0;
        $wpCustomer->updated_at = $cDate;
        $wpCustomer->save();

        return redirect()->back()->with('success', 'Work permit customer reminded successfully.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Work permit customer remind failed.');
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

        $wpCustomer = WPModel::findOrFail($id);
        $wpCustomer->delete();

        return redirect()->back()->with('success', 'Work permit customer deleted successfully.');
        } catch (Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Work permit customer delete failed.');
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
        return view('content.pages-misc-error');
    }

    public function wpRemind()
    {
        $wps = WPModel::with(['customer', 'contact', 'user'])
        ->where('is_Active', 1)
        ->whereDate('wpRemindDate', '<=', now())
        ->sortable()
        ->paginate(50);
        
      $customer_contacts = ContactModel::where('is_Active' , 1)->get();
      $users = User::all();

        return view('content.wps.remind', compact('wps', 'customer_contacts', 'users'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function wpShow(Request $request, $id)
    {

        $cDate = Carbon::now()->addHours(7);
        $formattedNowRent = $cDate->format('Y-m-d H:i:s');
        
        $statuss = WPModel::select('is_Active')->distinct()->get();

        $wps = WPModel::with('customer', 'user')
        ->where('wpID', $id)
        ->get();

        foreach ($wps as $wp)
        {
            $customerID = $wp->customerID;
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
        ->where(function ($q) use ($customerID) {
                $q->where('customerID', $customerID);
        })
        ->sortable()
        ->paginate(50);
        
        $customer_contacts = ContactModel::where('is_Active' , 1)->get();
        $users = User::all();

        return view('content.wps.show', 
        [
            'statuss' => $statuss,
            'wps' => $wps,
            'wp_logs' => $wp_logs,
            'customer_contacts' => $customer_contacts, 
            'users' => $users
        ]);
    }
}

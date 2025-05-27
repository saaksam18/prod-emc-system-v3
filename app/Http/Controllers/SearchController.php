<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\operations\CustomerModel;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        // Get the results from the database
        $results = CustomerModel::where('CustomerName', 'LIKE', '%' . $query . '%')->get();

        // Return the results to the view
        return response()->json($results);
    }
}

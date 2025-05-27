<?php

namespace App\Http\Controllers;

use App\Models\Motorbike;
use App\Models\Customer;
use Illuminate\Http\Request;

class MotorbikeController extends Controller
{
    public function index()
    {
        $motorbikes = Motorbike::all();

        return view('motorbikes.index', compact('motorbikes'));
    }

    public function show(Motorbike $motorbike)
    {
        $customer = Customer::find($motorbike->customer_id);

        return view('motorbikes.show', compact('motorbike', 'customer'));
    }

    public function create()
    {
        return view('motorbikes.create');
    }

    public function store(Request $request)
    {
        $motorbike = new Motorbike();
        $motorbike->make = $request->input('make');
        $motorbike->model = $request->input('model');
        $motorbike->customer_id = $request->input('customer_id');

        $motorbike->save();

        return redirect()->route('motorbikes.index');
    }

    public function edit(Motorbike $motorbike)
    {
        return view('motorbikes.edit', compact('motorbike'));
    }

    public function update(Request $request, Motorbike $motorbike)
    {
        $motorbike->make = $request->input('make');
        $motorbike->model = $request->input('model');
        $motorbike->customer_id = $request->input('customer_id');

        $motorbike->save();

        return redirect()->route('motorbikes.index');
    }

    public function destroy(Motorbike $motorbike)
    {
        $motorbike->delete();

        return redirect()->route('motorbikes.index');
    }
}
<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Supplier;

class HospitalSupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(15);
        return view('hospital.suppliers.index', compact('suppliers'));
    }
}
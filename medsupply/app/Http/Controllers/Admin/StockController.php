<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockItem;

class StockController extends Controller
{
    public function index()
    {
        $stocks = StockItem::with(['product', 'hospital'])->latest()->paginate(20);
        return view('admin.stock.index', compact('stocks'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        return view('purchase', compact('item'));
    }

}

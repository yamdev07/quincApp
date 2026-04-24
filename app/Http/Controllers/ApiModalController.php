<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;

class ApiModalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.trial']);
    }

    public function categories()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function suppliers()
    {
        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);

        return response()->json(['success' => true, 'data' => $suppliers]);
    }
}

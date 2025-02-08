<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index() {
        return response()->json([]);
    }
}
